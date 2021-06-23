var http = require('../../../../../utils/request.js');
var util = require('../../../../../utils/util.js');
var parse = require('../../../../../pages/common/parse/parse.js');

const app = getApp();
var timer = null;
Page({
  /**
   * 页面的初始数据
   */
  data: {
    gallery: [],
    bargainId:0,
    goodsId: 0,
    domain: app.globalData.domain,
    goods: [],
    choseSpec:[],
    day:'00',
    hour: '00',
    mini: '00',
    sec: '00',
    buyNum:1,
    currPage:1,
    price:0, // 成功砍价价格
    user:[],
    userInfo:[],
    article:'',
    helpsList:[],
    helpCurrPage:0,
    helpTotalPage:0,
    helpMoreText:'更多',
    bargainUserId:0,
    signType:0,
    bargainSuccessText:'恭喜！成功砍价',
    disableBtnClass:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var bargainId = options.id;
    that.setData({
      bargainId: options.id
    })
    var bargainUserId = options.bargainUserId;
    if (bargainUserId){
      that.setData({
        bargainUserId: bargainUserId
      })
    }
    that.getData(bargainId);
  },

  onShow: function (options) {
    
  },
  mainScroll: function (e) {
    var that = this;
    if (that.data.showType==1) {
      var scrollTop = e.detail.scrollTop;
      that.setData({
        currScrollTop: scrollTop,
        navOpacity: scrollTop / 100
      });
    }
  },
  
  onShareAppMessage: function () {
    var that = this;
    return {
      title: that.data.goods.goodsName,
      path: '/addons/package/pages/bargain/goods/detail?id=' + that.data.goods.bargainId + '&bargainUserId=' + that.data.bargainUserId
    }
  },
  previewImg: function (e) {
    var src = e.currentTarget.dataset.src;//获取data-src
    var imgList = e.currentTarget.dataset.list;//获取data-list
    //图片预览
    wx.previewImage({
      current: src, // 当前显示图片的http链接
      urls: imgList // 需要预览的图片http链接列表
    })
  },
  getGoodsDetail: function (){
    var that = this;
    var goodsId = that.data.goodsId;
    http.Post("weapp/goods/goodsDetail", { goodsId: goodsId }, function (res) {
      if (res.status == 1) {
        var goodsDesc = res.data.goodsDesc;
        if (goodsDesc) {
          parse.wxParse('goodsDesc', 'html', goodsDesc, that, 5);
        }
      }
    });
  },
  
  countDown: function (nowTime, endTime) {
    var that = this;
    var opts = {
      nowTime: nowTime,
      endTime: endTime,
      countDownType:1,
      callback: function (data) {
        if (data.last > 0) {
          that.setData({
            day:data.day,
            hour: data.hour,
            mini: data.mini,
            sec: data.sec
          });
        } else {
          that.data.goods['status'] = -1;
          that.setData({
            goods: that.data.goods,
          });
        }
      }
    };
    return util.countDown(opts);
  },
  //商品信息
  getData: function (id) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var bargainUserId = that.data.bargainUserId;
    wx.showLoading({ title: '加载中' });
    http.Post("addon/bargain-goods-wedetail", { tokenId: tokenId, id: id, bargainUserId: bargainUserId}, function (res) {
      wx.hideLoading();
      if (res.status >=0) {
        var goods = res.data.goods;
        var nowTime = new Date(Date.parse(res.data.nowTime.replace(/-/g, "/")));
        var startTime = new Date(Date.parse(goods.startTime.replace(/-/g, "/")));
        var endTime = new Date(Date.parse(goods.endTime.replace(/-/g, "/")));
        if (goods.status == 0) {
          timer = that.countDown(nowTime, startTime);
        } else {
          timer = that.countDown(nowTime, endTime);
        }
        var choseSpec = [];
        if (goods['isSpec']==1) {
          for (var i in goods['spec']) {
            var spec = goods['spec'][i];
            for (var j in spec['list']) {
              choseSpec.push(spec['list'][j]['itemName']);
            }
          }
        }
        var article = goods.article;
        if (article) {
          parse.wxParse('article', 'html', article, that, 5);
        }
        const gallery = goods.gallery.map(imgSrc => app.globalData.resourceDomain + imgSrc);
        that.setData({
          bargainUserId: res.data.bargainUserId,
          gallery: gallery,
          user:res.data.user,
          userInfo: res.data.userInfo,
          goods: goods,
          goodsId: goods.goodsId,
          signType: res.data.signType,
          choseSpec: choseSpec.join("，")
        });
        if (goods.status == -1 || goods.status == 0){
          that.setData({
            disableBtnClass:'btn-item-disable'
          })
        }
        setTimeout(function () {
          that.setData({
            reachBottomCnt: 1
          });
          that.getGoodsDetail();
          that.helpList();
        }, 1000);
      }
    });
  },
  viewImg({ currentTarget: { dataset: { src: current } } }) {
    const urls = this.data.gallery;
    wx.previewImage({
      current,
      urls
    })
  },
  //商品详情
  getDetail: function (goodsId) {
    var that = this;
    http.Post("weapp/goods/goodsDetail", { goodsId: goodsId }, function (res) {
      if (res.status == 1) {
        var goodsDesc = res.data.goodsDesc;
        if (goodsDesc) {
          parse.wxParse('goodsDesc', 'html', goodsDesc, that, 5);
        }
      }
    });
  },
  changeIptNum: function(e){
    var that = this;
    var canBuyNum = that.data.canBuyNum;
    var buyNum = that.data.buyNum;
    var type = e.currentTarget.dataset.type;
    if (type==-1){
      buyNum = (buyNum - 1) > 0 ? (buyNum - 1) : 1;
    } else if (type == 0) {
      var buyNum = e.detail.value;
      buyNum = (buyNum > canBuyNum) ? canBuyNum : buyNum;
    } else if (type == 1) {
      buyNum = (buyNum + 1) > canBuyNum ? canBuyNum : (buyNum + 1);
    }
    that.setData({
      buyNum: buyNum
    });
  },
  addCart: function(e){
    var that = this;
    var bargainId = that.data.bargainId;
    var buyNum = that.data.buyNum;
    var tokenId = app.globalData.tokenId;
    var sessionId = app.globalData.confInfo.sessionId;
    wx.showLoading({ title: '处理中' });
    http.Post("addon/bargain-carts-weAddCart", { sessionId: sessionId, tokenId: tokenId, id: bargainId, buyNum: buyNum, rnd: Math.random() }, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        wx.redirectTo({
          url: '/addons/package/pages/bargain/settlement/settlement'
        });
      }else{
        wx.showToast({
          title: res.msg,
          icon: 'none'
        })
      }
    });
  },
  pageSwitch:function(e){
    var id = e.currentTarget.dataset.id;
    this.setData({
      currPage:id
    });
  },
  firstKnife:function(e){
    var that = this;
    var id = that.data.goods.bargainId;
    http.Post("addon/bargain-bargains-weFirstKnife", { tokenId: app.globalData.tokenId ,id:id}, function (res) {
      if (res.status == 1) {
        that.setData({
          price:res.data
        });
        setTimeout(function () {
          wx.redirectTo({
            url: '/addons/package/pages/bargain/goods/detail?id='+id
          });
        }, 3000);
      }
    });
  },
  helpList: function (e) {
    var that = this;
    var params = {};
    var bargainUserId = that.data.bargainUserId;
    var id = that.data.goods.bargainId;
    params.tokenId = app.globalData.tokenId;
    params.bargainUserId = bargainUserId;
    params.id = id;
    params.pagesize = 10;
    params.page = parseInt(that.data.helpCurrPage) + 1;
    http.Post("addon/bargain-bargains-weHelpsList", params , function (res) {
      if (res.status == 1) {
        that.data.helpsList.push.apply(that.data.helpsList,res.data.data);  
        that.setData({
          helpsList:that.data.helpsList,
          helpCurrPage:res.data.current_page,
          helpTotalPage:res.data.last_page
        });
        if (res.data.data.length<10){
          that.setData({
            helpMoreText: '没有更多记录了'
          });
        }
      }
    });
  },
  moreHelpList:function(e){
    var helpCurrPage = parseInt(this.data.helpCurrPage);
    var helpTotalPage = parseInt(this.data.helpTotalPage);
    if (helpCurrPage + 1 > helpTotalPage){
      this.setData({
        helpMoreText:'没有更多记录了'
      });
      return;
    }else{
      this.helpList();
    }
  },
  // 参与
  toPartake:function(){
    var id = this.data.bargainId;
    wx.redirectTo({
      url: '/addons/package/pages/bargain/goods/detail?id=' + id
    });
  },
  // 帮砍一刀
  forAdd:function(){
    var that = this;
    var params = {};
    params.id = that.data.bargainId;
    params.bargainUserId = that.data.bargainUserId;
    params.signType = that.data.signType;
    params.openId = that.data.userInfo.weOpenId;
    params.userName = that.data.userInfo.userName;
    params.userPhoto = that.data.userInfo.userPhoto;
    params.tokenId = app.globalData.tokenId;
    http.Post("addon/bargain-bargains-weAddKnife", params , function (res) {
      app.prompt(res.msg);
      if (res.status == 1) {
        that.setData({
          bargainSuccessText:'成功帮TA砍价',
          price: res.data
        });
        that.reloadHelpList();
        that.bargainInfo();
        setTimeout(function () {
          that.setData({
            price: 0
          });
        }, 3000);
      }
    });
  },
  reloadHelpList:function(){
    this.setData({
      helpCurrPage: 0,
      helpTotalPage: 0
    });
    this.helpList();
  },
  bargainInfo:function(){
    var that = this;
    var params = {};
    params.id = that.data.bargainId;
    params.bargainUserId = that.data.bargainUserId;
    http.Post("addon/bargain-bargains-weBargainInfo", params, function (res) {
      if (res.status == 1) {
        var user = that.data.user;
        user.bargain.helpNum = res.data.helpNum;
        user.bargain.currPrice = res.data.currPrice;
        that.setData({
          user: user
        });
      }
    });
  }
})