var http = require('../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    termData: [{
        types: '',
        title: '全部'
      },
      {
        types: 'waitPay',
        title: '待付款'
      },
      {
        types: 'waitDeliver',
        title: '待发货'
      },
      {
        types: 'waitReceive',
        title: '待收货'
      },
      {
        types: 'finish',
        title: '已完成'
      },
      {
        types: 'abnormal',
        title: '取消拒收'
      },
    ],
    types: '',
    goodsLogo: null,
    domain: app.globalData.domain,
    orders: [],
    page: 0,
    currentId: 0,
    modifyFrame: false,
    modifyContent: 0,
    deliverId: 0,
    deliverIndex: [],
    deliverData: [],
    deliverFrame: false,
    deliverTakeFrame: false,
    deliverWords: '请选择快递公司',
    deliverContent: 0,
    refundFrame: false,
    refundContent: '',
    orderNo: 0,
    deliverMoney: 0,
    goodsMoney: 0,
    totalMoney: 0,
    realTotalMoney: 0,
    backMoney: 0,
    useScore: 0,
    scoreMoney: 0,
    refundIf: 1,
    deliverGoodsData: '',
    selectOrderGoodsIds: [],
    goodChecked: '',
    needExpress:true
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function() {
    this.getList();
    this.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo,
      resourceDomain: app.globalData.resourceDomain
    })
  },
  /**
   * 生命周期函数--监听小程序显示
   */
  onShow: function() {
    var that = this;
    that.setData({
      orders: [],
      deliverIndex: [],
      deliverData: [],
      page: 0
    });
    that.getList();
  },
  selected(e) {
    var that = this;
    var types = e.currentTarget.dataset.types;
    that.setData({
      types: types,
      orders: [],
      page: 0
    });
    that.getList();
  },
  //列表
  getList: function() {
    var that = this;
    var page = that.data.page;
    var types = that.data.types;
    var tokenId = app.globalData.tokenId;
    if (page == 0) wx.showLoading({
      title: '加载中'
    });
    page = page + 1;
    http.Post("weapp/orders/getsellerorderList", {
      tokenId: tokenId,
      types: types,
      deliverType: -1,
      payType: -1,
      page: page
    }, function(res) {
      if (res.status == 1) {
        var orders = that.data.orders;
        orders = orders.concat(res.data.data);
        that.setData({
          orders: orders,
          deliverIndex: res.data.deliverIndex,
          deliverData: res.data.deliverData,
          page: page
        })
      }
      wx.hideLoading();
    });
  },
  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {
    this.getList();
  },
  //详情
  todetail: function(e) {
    var orderId = e.currentTarget.dataset.orderid;
    wx.navigateTo({
      url: '../orders/orders-detail/orders-detail?orderId=' + orderId + '&types=2'
    })
  },
  //评价
  toevaluate: function(e) {
    var orderId = e.currentTarget.dataset.orderid;
    wx.navigateTo({
      url: '../orders/orders-appraises/orders-appraises?orderId=' + orderId
    })
  },
  //修改价格
  modify: function(e) {
    var that = this;
    var orderId = e.currentTarget.dataset.orderid;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/orders/getrefund", {
      tokenId: tokenId,
      id: orderId
    }, function(res) {
      if (res.status == 1) {
        that.setData({
          currentId: orderId,
          orderNo: res.data.orderNo,
          deliverMoney: res.data.deliverMoney,
          goodsMoney: res.data.goodsMoney,
          totalMoney: res.data.totalMoney,
          realTotalMoney: res.data.realTotalMoney,
          modifyFrame: true
        });
      }
    });
  },
  toModify: function() {
    var that = this;
    var orderId = that.data.currentId;
    var orderMoney = that.data.modifyContent;
    var tokenId = app.globalData.tokenId;
    if (orderMoney == 0) {
      app.prompt('请填写新价格');
      return false
    }
    http.Post("weapp/orders/editOrderMoney", {
      tokenId: tokenId,
      id: orderId,
      orderMoney: orderMoney
    }, function(res) {
      if (res.status == 1) {
        that.setData({
          orders: [],
          page: 0
        });
        that.hide();
        that.getList();
      } else {
        app.prompt(res.msg);
      }
    });
  },
  modifyText: function(e) {
    var that = this;
    that.setData({
      modifyContent: e.detail.value,
    });
  },
  //发货
  deliver: function(e) {
    var that = this;
    var orderId = e.currentTarget.dataset.orderid;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/orders/waitDeliverById", {
      tokenId: tokenId,
      id: orderId
    }, function(res) {
      if (res.status == 1) {
        for (var i = 0; i < res.data.list.length; i++) {
          if (res.data.list[i].goodsName.length > 3) {
            res.data.list[i].goodsName = res.data.list[i].goodsName.substring(0, 3) + '...';
          }
        }
        that.setData({
          deliverGoodsData: res.data.list,
          userAddress:res.data.userAddress,
          deliverFrame: res.data.deliverType == 0 ? true : false,
          deliverTakeFrame: res.data.deliverType == 1 ? true : false,
        });
      }
    });
    that.setData({
      currentId: orderId,
      deliverId: 0,
      deliverWords: '请选择快递公司'
    });
  },
  // 选择商品
  selectGoodsIdsChange: function(event) {
    var goodsId = event.currentTarget.dataset.goodsid;
    if (event.detail.value == 1) {
      this.data.selectOrderGoodsIds.push(goodsId);
    } else {
      var index = this.data.selectOrderGoodsIds.indexOf(goodsId);
      if (index > -1) {
        this.data.selectOrderGoodsIds.splice(index, 1);
      }
    }
    this.setData({
      selectOrderGoodsIds: this.data.selectOrderGoodsIds
    });
  },
  // 全选功能
  selectAllGoodsIdsChange: function(event) {
    this.setData({
      selectOrderGoodsIds: []
    });
    var goodsData = this.data.deliverGoodsData;
    var len = goodsData.length;
    for (let i = 0; i < len; i++) {
      if (goodsData[i].hasDeliver != true) {
        goodsData[i].goodChecked = !goodsData[i].goodChecked;
        if (goodsData[i].goodChecked == true){
          this.data.selectOrderGoodsIds.push(goodsData[i].id);
        }else{
          var index = this.data.selectOrderGoodsIds.indexOf(goodsData[i].id);
          if (index > -1) {
            this.data.selectOrderGoodsIds.splice(index, 1);
          }
        }
      }
    }
    this.setData({
      deliverGoodsData: goodsData,
      selectOrderGoodsIds: this.data.selectOrderGoodsIds
    });
  },
  // 是否需要物流
  needExpressChange:function(event){
    if (event.detail.value == 1) {
      this.setData({
        needExpress:true
      });
    }else{
      this.setData({
        needExpress: false
      });
    }  
  },
  toDeliver: function(e) {
    var that = this;
    var id = that.data.deliverId;
    var orderId = that.data.currentId;
    var expressId = that.data.deliverId;
    var expressNo = that.data.deliverContent;
    var tokenId = app.globalData.tokenId;
    var selectOrderGoodsIds = this.data.selectOrderGoodsIds;
    var deliverFrame = this.data.deliverFrame;
    var deliverTakeFrame = this.data.deliverTakeFrame;
    var deliverType = this.data.needExpress ? 1 : 0;
    var formId = e.detail.formId;
    // 商家发货
    if (deliverFrame){
      if (deliverType == 1){
        if (expressId == 0 || expressNo == 0) {
          app.prompt('请填写快递信息');
          return;
        } 
      }
      if (selectOrderGoodsIds == '') {
        app.prompt('请选择要发货的商品');
        return;
      } 
      http.Post("weapp/orders/deliver", {
        tokenId: tokenId,
        id: orderId,
        expressId: expressId,
        expressNo: expressNo,
        selectOrderGoodsIds: selectOrderGoodsIds,
        deliverType: deliverType,
        weappFormId:formId
      }, function (res) {
        if (res.status == 1) {
          that.setData({
            orders: [],
            page: 0
          });
          app.prompt('操作成功');
          setTimeout(function () {
            that.hide();
            that.getList();
          }, 1000);
        } else {
          app.prompt(res.msg);
        }
      });
    }
    // 自提
    if (deliverTakeFrame){
      this.setData({
        deliverTakeFrame:false
      });
      http.Post("weapp/orders/deliver", {
        tokenId: tokenId,
        id: orderId,
        expressId: 0,
        expressNo: ''
      }, function (res) {
        if (res.status == 1) {
          that.setData({
            orders: [],
            page: 0
          });
          app.prompt('操作成功');
          setTimeout(function () {
            that.hide();
            that.getList();
          }, 1000);
        } else {
          app.prompt(res.msg);
        }
      });
    }

  },
  deliverMenu: function(e) {
    var that = this;
    var deliverData = that.data.deliverData;
    var deliverIndex = that.data.deliverIndex;
    var index = e.detail.value;
    that.setData({
      deliverId: deliverIndex[index],
      deliverWords: deliverData[index]
    });
  },
  deliverText: function(e) {
    var that = this;
    that.setData({
      deliverContent: e.detail.value,
    });
  },
  //退款操作
  refund: function(e) {
    var that = this;
    var refundId = e.currentTarget.dataset.refundid;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/orders/toshoprefund", {
      tokenId: tokenId,
      id: refundId
    }, function(res) {
      if (res.status == 1) {
        that.setData({
          currentId: refundId,
          orderNo: res.data.orderNo,
          realTotalMoney: res.data.realTotalMoney,
          backMoney: res.data.backMoney,
          useScore: res.data.useScore,
          scoreMoney: res.data.scoreMoney,
          refundFrame: true,
          refundContent: '',
          refundIf: 1
        });
      }
    });
  },
  toRefund: function() {
    var that = this;
    var id = that.data.currentId;
    var refundStatus = that.data.refundIf;
    var content = that.data.refundContent;
    var tokenId = app.globalData.tokenId;
    if (refundStatus == -1 && content == '') {
      app.prompt('请填写原因');
      return false
    }
    http.Post("weapp/orderrefunds/shoprefund", {
      tokenId: tokenId,
      id: id,
      refundStatus: refundStatus,
      content: content
    }, function(res) {
      if (res.status == 1) {
        that.setData({
          orders: [],
          page: 0
        });
        that.hide();
        that.getList();
      } else {
        app.prompt(res.msg);
      }
    });
  },
  ifRefund: function(e) {
    var that = this;
    that.setData({
      refundIf: e.detail.value,
    });
  },
  refundText: function(e) {
    var that = this;
    that.setData({
      refundContent: e.detail.value,
    });
  },
  hide: function() {
    var that = this;
    that.setData({
      currentId: 0,
      modifyFrame: false,
      deliverFrame: false,
      deliverTakeFrame: false,
      refundFrame: false,
      needExpress: true
    });
  },
  //首页
  toIndex: function() {
    wx.navigateTo({
      url: '../../index/index'
    })
  }
})