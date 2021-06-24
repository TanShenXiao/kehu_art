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
    skillId:0,
    goodsId: 0,
    domain: app.globalData.domain,
    resourceDomain:app.globalData.resourceDomain,
    goods: [],
    choseSpec:[],
    page: 0,
    currentpage:0,
    lastPage: 0,
    hour: '00',
    mini: '00',
    sec: '00',
    msec: '0',
    seckillStatus:0,
    parameterType: '',
    specType:'',
    buyNum:1,
    canBuyNum:0,
    currStatus:1,
    isOpen:false,
    reachBottomCnt:0,
    scrollTop:0,
    currScrollTop: 0,
    navOpacity:0,
    activeId:1,
    showType:1,
    apprList:[]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var skillId = options.id;
    that.setData({
      skillId: options.id
    })
    that.getData(skillId);
    
  },

  onShow: function (options) {
    var that = this;
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
  mainScrollTolower(e) {
    
  },
  makePhone:function(e){
    if (app.globalData.isOpenIm) {
      if (app.globalData.tokenId != null) {
        var data = this.data.goods.shop;
        var shopId = data.shopId;
        if (app.globalData.userData.shopId == shopId) {
          // 客服身份无法进入自己所属店铺，防止自己与自己聊天
          wx.showToast({
            title: '无法进入该店铺',
            icon: 'none',
            duration: 2000
          })
          return;
        }
        var shopName = data.shopName;
        var shopImg = data.shopImg;
        var goodsId = e.currentTarget.dataset.goodsid;
        wx.navigateTo({
          url: '/addons/package/pages/wstim/wstim?goodsId=' + goodsId + '&shopId=' + shopId + '&shopName=' + shopName + '&shopImg=' + shopImg
        });
      } else {
        wx.navigateTo({
          url: '/pages/login/login'
        })
      }
    } else {
      var tel = e.currentTarget.dataset.tel;
      wx.makePhoneCall({
        phoneNumber: tel
      })
    }
  },
  
  onShareAppMessage: function () {
    
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
        that.setData({
          reachBottomCnt: 2
        });
      }
    });
  },
  
  //店铺
  toShop: function (e) {
    var shopId = e.currentTarget.dataset.shopid;
    wx.navigateTo({
      url: '/pages/shop/shop?shopId=' + shopId
    })
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
            hour: data.hour,
            mini: data.mini,
            sec: data.sec,
            msec: data.msec
          });
        } else {
          that.onLoad();
        }
      }
    };
    return util.countDown(opts);
  },
  //商品信息
  getData: function (id) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    wx.showLoading({ title: '加载中' });
    http.Post("addon/seckill-goods-wedetail", { tokenId: tokenId, id: id }, function (res) {
      wx.hideLoading();
      if (res.status >=0) {
        wx.setNavigationBarTitle({
          title: res.goodsName
        });
        var goods = res;
        var nowTime = new Date(Date.parse(goods.nowTime.replace(/-/g, "/")));
        var startTime = new Date(Date.parse(goods.startTime.replace(/-/g, "/")));
        var endTime = new Date(Date.parse(goods.endTime.replace(/-/g, "/")));
        var currStatus = goods.status;
        var canBuyNum = goods.canBuyNum;
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
        const gallery = goods.gallery.map(imgSrc => app.globalData.resourceDomain + imgSrc);
        that.setData({
          gallery: gallery,
          currStatus: currStatus,
          canBuyNum: canBuyNum,
          goods: goods,
          goodsId: goods.goodsId,
          choseSpec: choseSpec.join("，")
        });
        setTimeout(function () {
          that.setData({
            reachBottomCnt: 1
          });
          that.getGoodsDetail();
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


  toIndex: function () {
    wx.redirectTo({
      url: '/pages/index/index'
    })
  },
  //打开参数
  openParam: function (e) {
    var that = this;
    that.setData({
      isOpen:true,
      parameterType: 'active'
    });
  },
  openSpec: function(){
    var that = this;
    that.setData({
      isOpen: true,
      specType: 'active'
    });
  },
  maskClose: function (e) {
    var that = this;
    that.setData({
      isOpen: false,
      parameterType: 'active2',
      specType: 'active2'
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
    var skillId = that.data.skillId;
    var buyNum = that.data.buyNum;
    var tokenId = app.globalData.tokenId;
    var sessionId = app.globalData.confInfo.sessionId;
    wx.showLoading({ title: '处理中' });
    http.Post("addon/seckill-carts-weAddCart", { sessionId: sessionId,tokenId: tokenId, id: skillId, buyNum: buyNum, rnd: Math.random() }, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        wx.redirectTo({
          url: '/addons/package/pages/seckill/settlement/settlement'
        });
      }else{
        wx.showToast({
          title: res.msg,
          icon: 'none'
        })
      }
    });
  },
})