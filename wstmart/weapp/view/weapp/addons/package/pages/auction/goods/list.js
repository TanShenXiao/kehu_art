var http = require('../../../../../utils/request.js');
var util = require('../../../../../utils/util.js');
var app = getApp();
var timer = null;
Page({
  data: {
    resourceDomain: app.globalData.resourceDomain,
    domain: app.globalData.domain,//域名
    goodsCats:[], // 商品分类
    goodsCatsChildList:[], // 商品分类的子分类
    catId:0,
    goods: [],//商品
    nowTime: '',
    page: 0,
    lastPage: 0,
    scrollLeft:0,
    goodsName: '',//搜索关键词
    timers:'',
    parameterData: false,
    parameterStatus: false,
  },
  onLoad: function (options) {
    this.getData();
    this.goodsList();
  },
  onShow: function (options) {
    app.editTabBar();
  },
  getData:function(){
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Get("addon/auction-goods-weGoodsCats", {tokenId:tokenId}, function (res) {
      if (res.status == 1) {
          var goodsCats = res.data.goodscats;
          for(var i=0;i<goodsCats.length;i++){
              if(i==0){
                goodsCats[i].isSelected = 1;
              }else{
                goodsCats[i].isSelected = 0;
              }
          }
          that.setData({
            goodsCats: goodsCats,
            goodsCatsChildList: res.data.goodscats[0].childList
          })
      }
    });
  },
  //上拉加载
  onReachBottom: function () {
    var lastPage = this.data.lastPage;
    var page = this.data.page;
    if (page <= lastPage) {
      this.goodsList();
    }
  },
  countDown: function (nowTime, endTime,goodIndex){
    var that = this;
    var opts = {
      nowTime: nowTime,
      endTime: endTime,
      callback: function (data) {
        if (data.last > 0) {
          that.data.goods[goodIndex]['day'] = data.day;
          that.data.goods[goodIndex]['hour'] = data.hour;
          that.data.goods[goodIndex]['mini'] = data.mini;
          that.data.goods[goodIndex]['sec'] = data.sec;
          that.setData({
            goods:that.data.goods
          });
        }else{
          that.data.goods[goodIndex]['status'] = -1;
          that.setData({
            goods: that.data.goods,
          });
        }
      }
    };
    return util.countDown(opts);
  },
  toDetail:function({ currentTarget: { dataset: { id } } }){
    wx.navigateTo({
        url: `/addons/package/pages/auction/goods/detail?id=${id}`
    })
  },
  //商品
  goodsList: function () {
    var that = this;
    var params = {};
    var page = that.data.page + 1;
    params.catId = that.data.catId;
    params.tokenId = app.globalData.tokenId;
    params.goodsName = that.data.goodsName;
    params.timeId = that.data.timeId;
    params.page = page;
    // 清除定时器
    var timers = that.data.timers;
    for (var i = 0; i < timers.length; i++) {
      clearInterval(timers[i]);
    }
    wx.showLoading({ title: '加载中' });
    http.Post("addon/auction-goods-welists", params, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        var goods = that.data.goods;
        if(res.data.data.length > 0){
          goods = goods.concat(res.data.data);
          that.setData({
            goods: goods,
            page: page,
            lastPage: res.data.last_page
          });
          var nowTime = that.data.nowTime;
          var timers = [];
          for (var i = 0; i < goods.length; i++) {
            var startTime = new Date(Date.parse(goods[i].startTime.replace(/-/g, "/")));
            var endTime = new Date(Date.parse(goods[i].endTime.replace(/-/g, "/")));
            if(goods[i].status!=-1){
              if (goods[i].status == '0') {
                timer = that.countDown(nowTime, startTime, i);
              } else {
                timer = that.countDown(nowTime, endTime, i);
              } 
              timers.push(timer);
            }
          }
          that.setData({
            timers: timers
          });
        }
      }
    });
  },
  keywordInput: function (e) {
    var that = this;
    that.setData({
      goodsName: e.detail.value,
    });
  },
  searchGood:function(){
    var that = this;
    that.setData({
      goods: [],//商品
      page: 0,
      lastPage: 0,
      catId:0
    });
    that.goodsList();
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
  goodsCat:function(e){
    var catId = e.currentTarget.dataset.catid;
    this.setData({
      catId:catId,
      parameterStatus: false,
      backStatus: true,
      goods: [],//商品
      page: 0,
      lastPage: 0
    });
    this.goodsList();
  },
  showRight:function(e){
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
});