var http = require('../../../../../utils/request.js');
var util = require('../../../../../utils/util.js');
var app = getApp();
var timer = null;
Page({
  data: {
    domain: app.globalData.domain,//域名
    resourceDomain:app.globalData.resourceDomain,//资源路径
    secTimes: [],//秒杀时段
    currCatId: 0,//当前分类
    goods: [],//商品
    currStatus: 0,
    nowTime: '',
    stime: '',
    etime: '',
    timeId: 0,
    page: 0,
    totalCnt: -1,
    lastPage: 0,
    hour: '00',
    mini: '00',
    sec: '00',
    scrollLeft:0
  },
  onLoad: function (options) {
    this.getTimes();
    
  },

  //上拉加载
  onReachBottom: function () {
    var lastPage = this.data.lastPage;
    var page = this.data.page;
    if (page <= lastPage) {
      this.goodsList();
    }
  },
  onPullDownRefresh: function () {
    var that = this;
    that.setData({
      goods: [],
      page: 0
    });
    that.goodsList();
  },
  //秒杀时段
  getTimes: function () {
    var that = this;
    var data = {};
    http.Post("addon/seckill-goods-weTimes", data, function (res) {
      if (res.status == 1) {
        var times = res.data;
        var timeId = 0;
        var status = 0;
        var stime = '';
        var etime = '';
        var w = 150;
        var width = 0;
        for (var i in times){
          if (times[i].status == 1) {
            timeId = times[i].id;
            status = times[i].status;
            stime = times[i].startTime;
            etime = times[i].endTime;
            var index = (i > 2) ? i : 0
            width = w * (index - 2);
            break;
          };
        }
        that.setData({
          secTimes: times,
          timeId: timeId,
          stime: stime,
          etime: etime,
          scrollLeft: width,
          currStatus: status
        });
        that.getNowTime();
        that.goodsList();
      }
    });
  },
  switchTab: function(e){
    
    var that = this;
    if (timer) clearInterval(timer);
    var vstatus = e.currentTarget.dataset.status;
    
    var w = 150;
    var index = e.currentTarget.dataset.key;
    index = (index > 2) ? index : 0
    var width = w * (index - 2);
   
    var timeId = e.currentTarget.dataset.timeid;
    var stime = e.currentTarget.dataset.stime;
    var etime = e.currentTarget.dataset.etime;
    that.setData({
      currStatus: vstatus,
      timeId: timeId,
      stime: stime,
      etime: etime,
      hour: '00',
      mini: '00',
      sec: '00',
      page: 0,
      lastPage: 0,
      scrollLeft: width,
      goods: []
    });
    if (vstatus<2)that.getNowTime();
    that.goodsList();
  },
  getNowTime: function(){
    var that = this;
    http.Post('addon/seckill-goods-weGetNowTime', {}, function (res) {
      var nowTime = new Date(Date.parse(res.data.nowTime.replace(/-/g, "/")));
      var startTime = new Date(Date.parse(that.data.stime.replace(/-/g, "/")));
      var endTime = new Date(Date.parse(that.data.etime.replace(/-/g, "/")));
      if (that.data.currStatus == '0') {
        timer = that.countDown(nowTime, startTime);
      } else {
        timer = that.countDown(nowTime, endTime);
      }
    });
  },
  countDown: function (nowTime, endTime){
    var that = this;
    var opts = {
      nowTime: nowTime,
      endTime: endTime,
      callback: function (data) {
        if (data.last > 0) {
          that.setData({
            hour: data.hour,
            mini: data.mini,
            sec: data.sec
          });
        }else{
          that.onLoad();
        }
      }
    };
    return util.countDown(opts);
  },
  //商品
  goodsList: function () {
    var that = this;
    var params = {};
    var page = that.data.page + 1;
    params.catId = that.data.currCatId;
    params.tokenId = app.globalData.tokenId;
    params.timeId = that.data.timeId;
    params.page = page;
    that.setData({ load: 0 });
    http.Post("addon/seckill-goods-wePageQuery", params, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        var goods = that.data.goods;
        goods = goods.concat(res.data);
        var load = res.total == 0 ? 2 : (res.current_page == res.last_page ? 1 : 0);
        that.setData({
          goods: goods,
          page: page,
          lastPage: res.last_page,
          totalCnt: res.total,
          load: load
        });
      }
    });
  },

  //初始页面加载
  initial: function () {
    var that = this;
    that.setData({
      goods: [],//商品
      page: 0,
      lastPage: 0
    });
    that.goodsList();
  }
});