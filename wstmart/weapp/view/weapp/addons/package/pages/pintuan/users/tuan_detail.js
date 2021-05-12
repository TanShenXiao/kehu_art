var http = require('../../../../../utils/request.js');
var util = require('../../../../../utils/util.js');
var app = getApp();
var timer = null;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    domain: app.globalData.domain,//域名
    resourceDomain: app.globalData.resourceDomain,//资源路径
    tuan: [],
    tuanId: 0,
    tuanNo: 0,
    currPuId: 0,
    maxPuId: 0,
    catId: 0,
    page: 0,
    lastPage: 0,
    totalCnt: -1,
    glist: [],
    glikes:[],
    hour: '00',
    mini: '00',
    sec: '00',
    msec: '00',
    nowTime: '',
    stime: '',
    etime: '',
    pkey:'',
    animationData: false,
    showModalStatus: false,
    backStatus: true,
    storeStatus: true,
    detailsStatus: false,
    commentStatus: false,
    num: 1,
    cartsType: 0,
    goodsPrice: 0,
    marketPrice: 0,
    tuanStock: 0,
    minStock: 1,
    maxStock: 0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    if (options.vtype){
      that.getTuanInfo(options.tuanNo);
    }else{
      app.getdata(function (res) {
        that.setData({
          tuanNo: options.tuanNo,
          confInfo: app.globalData.confInfo,
          goodsLogo: app.globalData.confInfo.goodsLogo,
          shopLogo: app.globalData.confInfo.shopLogo,
          resourceDomain: app.globalData.resourceDomain
        });
        that.getTuanInfo(options.tuanNo);
      })
    }
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    var that = this;
    return {
      title: that.data.tuan.goodsName,
      path: '/addons/package/pages/pintuan/users/tuan_detail?tuanNo=' + that.data.tuanNo 
    }
  },

  goodsList: function (){
    var that = this;
    var params = {};
    var page = that.data.page + 1;
    params.catId = that.data.catId;
    params.tokenId = app.globalData.tokenId;
    params.page = page;
    wx.showLoading({ title: '加载中' });
    http.Post("addon/pintuan-goods-wePintuanList", params, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        var goods = that.data.glikes;
        goods = goods.concat(res.data.data);
        that.setData({
          glikes: goods,
          page: page,
          totalCnt: res.data.total,
          lastPage: res.data.last_page
        });
      }
    });
  },
  getTuanInfo: function (tuanNo) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    wx.showLoading({ title: '加载中' });
    http.Post("addon/pintuan-goods-weTuanDetail", { tokenId: tokenId, tuanNo: tuanNo }, function (res) {
      wx.hideLoading();
      if (res.status >= 0) {
        const gallery = res.data.goods.gallery.map(imgSrc => app.globalData.resourceDomain + imgSrc);
        that.setData({
          tuan: res.data,
          tuanId: res.data.tuanId,
          catId: res.data.goodsCatId,
          gallery: gallery,
          pkey: res.data.pkey,
          goodsSpecId: res.data.goods.goodsSpecId ? res.data.goods.goodsSpecId:0,
          goodsId: res.data.goodsId,
          descImage: res.data.goodsImg,
          stime: res.data.createTime,
          etime: res.data.endTime,
          tuanStock: (res.data.goods.goodsNum - res.data.goods.saleNum > 0) ? (res.data.goods.goodsNum - res.data.goods.saleNum) : 0
        });
        that.getNowTime();
        that.goodsList();
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
  getNowTime: function () {
    var that = this;
    http.Post('addon/pintuan-goods-weGetNowTime', {}, function (res) {
      var nowTime = new Date(Date.parse(res.data.nowTime.replace(/-/g, "/")));
      var startTime = new Date(Date.parse(that.data.stime.replace(/-/g, "/")));
      var endTime = new Date(Date.parse(that.data.etime.replace(/-/g, "/")));
      
        timer = that.countDown(nowTime, endTime);
      
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
  choicePay: function (e) {
    var pkey = e.currentTarget.dataset.pkey;
    wx.navigateTo({
      url: '/addons/package/pages/pintuan/payment/payment?pkey=' + pkey
    });
  },
  /*加入购物车 */
  powerDrawer: function (e) {
    var that = this;
    var statu = e.currentTarget.dataset.statu;
    var cartsType = e.currentTarget.dataset.type;
    var maxStock = that.data.tuanStock;
    var goodsPrice = that.data.tuan.tuanPrice;
    that.setData({
      maxStock: maxStock,
      goodsPrice: goodsPrice,
      cartsType: cartsType
    });
    this.util(statu);
  },
  util: function (statu, types) {
    /* 动画部分 */
    // 第1步：创建动画实例   
    var animation = wx.createAnimation({
      duration: 300,  //动画时长  
      timingFunction: "linear", //线性  
      delay: 0  //0则不延迟  
    });

    // 第2步：这个动画实例赋给当前的动画实例  
    this.animation = animation;

    // 第3步：执行第一组动画：Y轴偏移260px后(盒子高度是240px)，停  
    animation.translateY(400).step();

    // 第4步：导出动画对象赋给数据对象储存  
    this.setData({
      animationData: animation.export()
    })

    // 第5步：设置定时器到指定时候后，执行第二组动画  
    setTimeout(function () {
      // 执行第二组动画：Y轴不偏移，停  
      animation.translateY(0).step()
      // 给数据对象储存的第一组动画，更替为执行完第二组动画的动画对象  
      this.setData({
        animationData: animation.export()
      })

      //关闭抽屉  
      if (statu == "close") {
        this.setData({
          showModalStatus: false,
          backStatus: true
        });
      }
    }.bind(this), 200)
    // 显示抽屉  
    if (statu == "open") {
      this.setData({
        showModalStatus: true,
        backStatus: false
      });
    }
  },
  /*数量变化 */
  changeNum(e) {
    var that = this;
    var mode = e.currentTarget.dataset.mode;
    var minStock = e.currentTarget.dataset.min;
    var maxStock = e.currentTarget.dataset.max;
    var num = that.data.num;
    if (mode == 'plus' && maxStock > num) {
      num++;
    } else if (mode == 'reduce' && num > minStock) {
      num--;
    } else {
      return false;
    }
    that.setData({
      num: num
    });
  },
  //加入购物车
  join: function (e) {
    var that = this;
    var cartsType = that.data.cartsType;
    var tuanId = that.data.tuanId;
    var tuanNo = that.data.tuanNo;
    var goodsType = that.data.goodsType;
    var isSpec = that.data.isSpec;
    var goodsSpecId = 0;
    var num = that.data.num;
    var sessionId = app.globalData.confInfo.sessionId;
    var tokenId = app.globalData.tokenId;
    if (isSpec == 1) {
      goodsSpecId = that.data.goodsSpecId;
    }
    var params = { tokenId: tokenId, id: tuanId, buyNum: num, tuanType: 1, sessionId: sessionId, rnd: Math.random() };
    if (cartsType==0){
      params.tuanNo = tuanNo;
    }
    
    http.Post("addon/pintuan-carts-weAddCart", params, function (res) {
      if (res.status == 1) {
        wx.navigateTo({
          url: '/addons/package/pages/pintuan/settlement/settlement'
        });
        that.util('close');
      } else {
        app.prompt(res.msg);
        that.getData(tuanId);
      }
    });
  }
})