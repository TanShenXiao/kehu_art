var http = require('../../../../../utils/request.js');
var util = require('../../../../../utils/util.js');
var app = getApp();
var ivt = null;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    domain: app.globalData.domain,//域名
    resourceDomain: app.globalData.resourceDomain,//资源路径
    userPhoto: app.globalData.resourceDomain + app.globalData.confInfo.userLogo,
    currCatId: 0,//当前分类
    goods: [],//商品
    goodsCats: [], // 商品分类
    goodsCatsChildList: [], // 商品分类的子分类
    totalCnt:-1,
    currStatus: 0,
    page: 0,
    lastPage: 0,
    scrollLeft: 0,
    keyword: '',//搜索关键词
    parameterData: false,
    parameterStatus: false,
    currPuId:0,
    maxPuId:0,
    maxCheckNo:15,
    tuanMsg:'',
    isShow: false,
    load: 2
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.getData();
    this.goodsList();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    var lastPage = this.data.lastPage;
    var page = this.data.page;
    if (page <= lastPage) {
      this.goodsList();
    }
  },
  /**
   * 页面下拉刷新
   */
  onPullDownRefresh: function () {
    var that = this;
    that.setData({
      goods: [],
      currCatId: 0,
      page: 0
    });
    that.goodsList();
  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  },
  //搜索
  onSearch: function (e) {
    var that = this;
    that.setData({
      goods: [],
      page: 0
    });
    that.goodsList();
  },
  nameInput: function (e) {
    var that = this;
    that.setData({
      keyword: e.detail.value,
    });
  },
  
  getData: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("addon/pintuan-goods-welists", { tokenId: tokenId }, function (res) {
      if (res.status == 1) {
        var goodsCats = res.data.goodscats;
        for (var i = 0; i < goodsCats.length; i++) {
          if (i == 0) {
            goodsCats[i].isSelected = 1;
          } else {
            goodsCats[i].isSelected = 0;
          }
        }
        that.setData({
          goodsCats: goodsCats,
          currPuId: res.data.maxPuId,
          maxPuId: res.data.maxPuId,
          goodsCatsChildList: res.data.goodscats[0].childList
        });
        that.getLastTuan();
      }
    });
  },
  getLastTuan: function () {
    var that = this;
    var maxCheckNo = that.data.maxCheckNo;
    ivt = setInterval(function () {
      var currPuId = that.data.currPuId;
      var maxPuId = that.data.maxPuId;
      if (maxCheckNo > 0) {
        that.lastTuan(0, currPuId, maxPuId, 5000);
      }
    }, 10000);
  },

  lastTuan: function (tuanId, currPuId, maxPuId, laytime) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("addon/pintuan-goods-weGetLastTuan",
      { tokenId: tokenId, tuanId: tuanId, currPuId: currPuId, maxPuId: maxPuId, rnd: Math.random() }, function (res) {
        var maxCheckNo = that.data.maxCheckNo;
        var userPhoto = that.data.userPhoto;
        var tuanMsg = "";
        var currPuId = 0;
        var isShow = false;
        maxCheckNo = maxCheckNo - 1;
        if (maxCheckNo == 0) {
          maxCheckNo = 15;
          currPuId = maxPuId;
        }
        if (res.status == 1) {
          var tflag = res.data.tflag;
          if (tflag == 1) {
            maxPuId = res.data.maxPuId;
          } else {
            currPuId = res.data.currPuId;
          }
          userPhoto = res.data.puser.userPhoto;
          tuanMsg = res.data.tmsg;
          isShow = true;
          
        } else {
          currPuId = maxPuId;
          isShow = false;
        }
        that.setData({
          userPhoto: app.userPhoto(userPhoto),
          tuanMsg: tuanMsg,
          maxPuId: maxPuId,
          currPuId: currPuId,
          isShow: isShow
        });
        setTimeout(function () {
          that.setData({
            isShow: false
          });
        }, laytime);
      });
  },
  goodsList: function () {
    var that = this;
    var params = {};
    var page = that.data.page + 1;
    params.catId = that.data.currCatId;
    params.tokenId = app.globalData.tokenId;
    params.goodsName = that.data.keyword;
    params.page = page;
    that.setData({ load: 0 });
    http.Post("addon/pintuan-goods-wePintuanList", params, function (res) {
      if (res.status == 1) {
        var goods = that.data.goods;
        goods = goods.concat(res.data.data);
        var load = res.data.total == 0 ? 2 : (res.data.current_page == res.data.last_page ? 1 : 0);
        that.setData({
          goods: goods,
          page: page,
          totalCnt: res.data.total,
          lastPage: res.data.last_page,
          load: load
        });
      }
    });
  },
  /*商品分类*/
  parameter: function (e) {
    var statu = e.currentTarget.dataset.statu;
    this.parameterPopup(statu)
  },
  parameterPopup: function (statu) {
    /* 动画部分 */
    var animation = wx.createAnimation({
      duration: 300,  //动画时长  
      timingFunction: "linear", //线性  
      delay: 0  //0则不延迟  
    });
    animation.translateX(600).step();
    this.setData({
      parameterData: animation.export()
    })
    setTimeout(function () {
      animation.translateX(0).step()
      this.setData({
        parameterData: animation.export()
      })
      //关闭抽屉  
      if (statu == "close") {
        this.setData({
          parameterStatus: false,
          backStatus: true
        });
      }
    }.bind(this), 200)
    // 显示抽屉  
    if (statu == "open") {
      this.setData({
        parameterStatus: true,
        backStatus: false
      });
    }
  },
  goodsCat: function (e) {
    var catId = e.currentTarget.dataset.catid;
    this.setData({
      currCatId: catId,
      parameterStatus: false,
      backStatus: true,
      goods: [],//商品
      page: 0,
      lastPage: 0
    });
    this.goodsList();
  },
  showRight: function (e) {
    var index = e.currentTarget.dataset.index;
    var goodsCats = this.data.goodsCats;
    for (var i = 0; i < goodsCats.length; i++) {
      if (i == index) {
        goodsCats[i].isSelected = 1;
      } else {
        goodsCats[i].isSelected = 0;
      }
    }
    var goodsCats = this.data.goodsCats;
    var goodsCatsChildList = goodsCats[index].childList;
    this.setData({
      goodsCats,
      goodsCatsChildList
    });
  }
})