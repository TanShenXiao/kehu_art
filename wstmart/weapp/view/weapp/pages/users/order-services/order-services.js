var http = require('../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    goodsLogo: null,
    domain: app.globalData.domain,
    orders: [],
    page: 0,
    currentId:0,
    rejectId: 0,
    rejectIndex: [],
    rejectData: [],
    rejectFrame: false,
    rejectWords: '请选择您拒收的原因',
    rejectContent:''
  }, 
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo,
      resourceDomain:app.globalData.resourceDomain
    });
  },
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function () {
    var that = this;
    that.setData({
      orders: [],
      rejectIndex: [],
      rejectData: [],
      page: 0,
    });
    that.getList();
    that.getRejectReason();
  },
  //列表
  getList: function () {
    var that = this;
    var page = that.data.page;
    var tokenId = app.globalData.tokenId;
    if(page == 0)wx.showLoading({ title: '加载中' });
    page = page + 1;
    http.Post("weapp/orderservices/pagequery", { tokenId: tokenId, page: page }, function (res) {
      if (res.status == 1) {
        var orders = that.data.orders;
        orders = orders.concat(res.data.data);
        that.setData({
          orders: orders,
          page: page
        })
      }
      wx.hideLoading();
    });
  },
  getRejectReason:function(){
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/orderservices/getRejectReason", { tokenId }, function (res) {
      if (res.status == 1) {
        const { data } = res;
        let _name = [];
        let _val = [];
        for(let i in data){
          _val.push(data[i].dataVal);
          _name.push(data[i].dataName);
        }
        that.setData({
          rejectIndex: _val,
          rejectData: _name,
        })
      }
    });
  },
  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    this.getList();
  },
  detail:function({ currentTarget: { dataset: { id } } }){
    wx.navigateTo({
      url: `./os-detail/os-detail?id=${id}`
    })
  },
  //确认收货
  confirm: function ({ currentTarget: { dataset: { id } }}) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    let postData = {
        id,
        isUserAccept:1,
        userRejectType:0,
        tokenId
    }
    wx.showModal({
      title: '提示',
      content: '你确定已收货吗?',
      success: function (res) {
        if (res.confirm) {
          http.Post("weapp/orderservices/userReceive", postData, function (res) {
            if (res.status == 1) {
              that.setData({
                orders: [],
                page: 0
              });
              that.getList();
            } else {
              app.prompt(res.msg);
            }
          });
        }
      }
    })
  },
  reject: function ({ currentTarget: { dataset: { id } }}) {
    var that = this;
    that.setData({
      currentId: id,
      rejectFrame: true,
      rejectId: 0,
      rejectWords: '请选择您拒收订单的原因'
    });
  },

  toReject: function () {
    var that = this;
    var rejectId = that.data.rejectId;
    var id = that.data.currentId;
    var content = that.data.rejectContent;
    var tokenId = app.globalData.tokenId;
    
    if (rejectId == 0) {
      app.prompt('请选择原因');
      return false
    }
    if (rejectId == 10000 && content=='') {
      app.prompt('请填写原因');
      return false
    }
    let postData = {
        id,
        isUserAccept:-1,
        userRejectType:rejectId,
        userRejectOther:content,
        tokenId
    }
    http.Post("weapp/orderservices/userReceive", postData, function (res) {
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
  rejectMenu: function (e) {
    var that = this;
    var rejectData = that.data.rejectData;
    var rejectIndex = that.data.rejectIndex;
    var index = e.detail.value;
    that.setData({
      rejectId: rejectIndex[index],
      rejectWords: rejectData[index]
    });
  },
  rejectText: function (e) {
    var that = this;
    that.setData({
      rejectContent: e.detail.value,
    });
  },
  hide: function () {
    var that = this;
    that.setData({
      currentId: 0,
      rejectFrame: false,
      isScroll:true
    });
  },


})