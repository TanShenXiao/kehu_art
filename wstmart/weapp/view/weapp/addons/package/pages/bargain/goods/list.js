var http = require('../../../../../utils/request.js');
var util = require('../../../../../utils/util.js');
var app = getApp();
var timer = null;
Page({
  data: {
    domain: app.globalData.domain,//域名
    ads:[], // 砍价广告
    indicatorDots: true,
    autoplay: true,
    interval: 3000,
    duration: 500,
    circular: true,
    goodsCats:[], // 商品分类
    goodsCatsChildList:[], // 商品分类的子分类
    catId:0,
    goods: [],//商品
    nowTime: '',
    page: 0,
    scrollLeft:0,
    goodsName: '',//搜索关键词
    timers:'',
    parameterData: false,
    parameterStatus: false,
    currPage:0,
    totalPage:0,
    moreText: '加载中',
    totalCnt:-1
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
    http.Get("addon/bargain-goods-welists", {tokenId:tokenId}, function (res) {
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
            ads:res.data.ads,
            goodsCats: goodsCats,
            goodsCatsChildList: res.data.goodscats[0].childList
          })
      }
    });
  },
  //上拉加载
  onReachBottom: function () {
    var currPage = parseInt(this.data.currPage);
    var totalPage = parseInt(this.data.totalPage);
    if (currPage + 1 > totalPage) {
      this.setData({
        moreText: '加载完啦'
      });
      return;
    } else {
      this.goodsList();
    }
  },
  getNowTime: function(){
    var that = this;
    http.Post('addon/seckill-goods-weGetNowTime', {}, function (res) {
      var nowTime = new Date(Date.parse(res.data.nowTime.replace(/-/g, "/")));
      that.setData({
        nowTime:nowTime
      });
    });
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
            goods:that.data.goods,
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
  //商品
  goodsList: function () {
    var that = this;
    var params = {};
    params.catId = that.data.catId;
    params.tokenId = app.globalData.tokenId;
    params.goodsName = that.data.goodsName;
    params.timeId = that.data.timeId;
    params.page = parseInt(that.data.currPage) + 1;
    params.pagesize = 10;
    // 清除定时器
    var timers = that.data.timers;
    for (var i = 0; i < timers.length; i++) {
      clearInterval(timers[i]);
    }
    wx.showLoading({ title: '加载中' });
    http.Post("addon/bargain-goods-weBargainlists", params, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        that.data.goods.push.apply(that.data.goods, res.data.data);  
        that.setData({
          goods: that.data.goods,
          currPage: res.data.current_page,
          totalPage: res.data.last_page,
          totalCnt: res.data.total,
        });
        var goods = that.data.goods;
        var nowTime = that.data.nowTime;
        var timers = [];
        for (var i = 0; i < goods.length; i++) {
          var startTime = new Date(Date.parse(goods[i].startTime.replace(/-/g, "/")));
          var endTime = new Date(Date.parse(goods[i].endTime.replace(/-/g, "/")));
          if (goods[i].status == '0') {
            timer = that.countDown(nowTime, startTime, i);
          } else {
            timer = that.countDown(nowTime, endTime, i);
          } 
          timers.push(timer);
        }
        that.setData({
          timers: timers
        });
        if (res.data.data.length < 10) {
          that.setData({
            moreText: '加载完啦'
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
      currPage: 0,
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
      currPage: 0,
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
  },
  viewGoodsDetail:function(e){
    var bargainId = e.currentTarget.dataset.bargainid;
    wx.navigateTo({
      url: './detail?id=' + bargainId
    });
  },
});