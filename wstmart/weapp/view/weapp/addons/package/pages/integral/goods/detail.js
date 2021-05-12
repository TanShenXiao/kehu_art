var http = require('../../../../../utils/request.js');
var util = require('../../../../../utils/util.js');
var parse = require('../../../../../pages/common/parse/parse.js');
//获取应用实例
const app = getApp();
var timer = null;
Page({
  /**
   * 页面的初始数据
   */
  data: {
    gallery: [],
    integralId: 0,
    goodsId: 0,
    catId: 0,
    isSpec: 0,
    goodsType: 0,
    goodsLogo: null,
    shopLogo: null,
    domain: app.globalData.domain,
    resourceDomain: app.globalData.resourceDomain,//资源路径
    goods: [],
    goodsDesc: '',
    evaluate: [],
    currPage: 0,
    frontPage: 0,
    confInfo: null,
    moreStatus: false,
    moreGoods: 1,
    parameterData: false,
    parameterStatus: false,
    animationData: false,
    showModalStatus: false,
    backStatus: true,
    storeStatus: true,
    detailsStatus: false,
    commentStatus: false,
    num: 1,
    minStock: 1,
    maxStock: 0,
    goodsPrice: 0,
    marketPrice: 0,
    goodsStock: 0,
    arraySpec: {},
    windowStatus: true,
    favGood: 0,
    descImage: '',
    type: '',
    pagesize: 10,
    tokenId:null
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    app.getdata(function (res) {
      that.setData({
        integralId: options.id,
        tokenId: app.globalData.tokenId,
        confInfo: app.globalData.confInfo,
        goodsLogo: app.globalData.confInfo.goodsLogo,
        shopLogo: app.globalData.confInfo.shopLogo,
        resourceDomain: app.globalData.resourceDomain
      })
      that.getData(options.id);
    })
  },
  onShow: function () {
    var that = this;
    that.setData({
      tokenId: app.globalData.tokenId
    });
  },
  //商品信息
  getData: function (id) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    wx.showLoading({ title: '加载中' });
    http.Post("addon/integral-goods-weDetail", { tokenId: tokenId, id: id }, function (res) {
      wx.hideLoading();
      if (res.status >= 0) {
        that.title(res.goodsName);
        const gallery = res.gallery.map(imgSrc => app.globalData.resourceDomain + imgSrc);
        that.setData({
          goods: res,
          catId: res.goodsCatId,
          isSpec: res.isSpec,
          gallery: gallery,
          goodsType: res.goodsType,
          favGood: res.favGood,
          goodsId: res.goodsId,
          descImage: res.goodsImg,
          goodsStock: (res.totalNum - res.orderNum) ? (res.totalNum - res.orderNum):0
        });
      }
    });
  },
  //标题
  title: function (e) {
    wx.setNavigationBarTitle({
      title: e
    })
  },
  viewImg({ currentTarget: { dataset: { src: current } } }) {
    const urls = this.data.gallery;
    wx.previewImage({
      current,
      urls
    })
  },
  
  //商品详情
  getDetail: function (id) {
    var that = this;
    http.Post("weapp/goods/goodsDetail", { goodsId: id }, function (res) {
      if (res.status == 1) {
        var goodsDesc = res.data.goodsDesc;
        if (goodsDesc) {
          parse.wxParse('goodsDesc', 'html', goodsDesc, that, 5);
        }
      }
    });
  },
  /*关注 */
  attStatus: function (data, url) {
    var that = this;
    var goodsId = this.data.goodsId;
    http.Post('weapp/Favorites/' + url, data, function (res) {
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          success: function () {
            if (data.favType == 1) {
              that.setData({
                favGood: 0
              })
            } else {
              that.setData({
                favGood: res.data.fId
              })
            }
          }
        })
      } else {
        wx.showModal({
          title: '提示',
          content: res.msg,
        })
      }
    })
  },
  selectStatus: function (e) {
    var favGood = this.data.favGood;
    var data = this.data.data;

    if (favGood > 0) {
      data = {
        type: 0,
        favType: 1,
        tokenId: app.globalData.tokenId,
        id: favGood
      }
      this.attStatus(data, 'cancel')
    } else {
      data = {
        type: 0,
        favType: 0,
        tokenId: app.globalData.tokenId,
        id: this.data.goodsId
      }
      this.attStatus(data, 'add')
    }
  },
  //评价列表
  getEvaluate: function (id, page) {
    var that = this;
    var frontPage = that.data.frontPage;
    var type = that.data.type;
    var pagesize = that.data.pagesize;
    if (frontPage != page || page == 0) {
      that.setData({ frontPage: page, })
      var page = page + 1;
      http.Post("weapp/goodsappraises/getById", { pagesize: pagesize, goodsId: id, page: page, type: type }, function (res) {
        if (res.status == 1) {
          var list = that.data.evaluate;
          var appraises = res.data;
          var data = res.data.data;
          if (data.length > 0) {
            list = list.concat(data);
            for (var i in list) {
              list[i]['userPhoto'] = app.userPhoto(list[i]['userPhoto']);
            }
            that.setData({
              page: page,
              evaluate: list,
              appraises: appraises,
              totalPage: res.data.last_page
            })
          }
        }
      });
    }
  },
  loadEvaluate: function (e) {
    var that = this;
    this.getEvaluate(that.data.goodsId, that.data.currPage);
  },

  //菜单
  inMore: function (e) {
    var statu = e.currentTarget.dataset.statu;
    if (statu == 1) {
      this.setData({ moreStatus: true });
    } else {
      this.setData({ moreStatus: false });
    }
  },
  //首页
  toIndex: function (e) {
    wx.navigateTo({
      url: '/pages/index/index'
    });
  },
  //分类
  toClassify: function (e) {
    wx.navigateTo({
      url: '/pages/classify/classify'
    });
  },
  //购物车
  toCart: function (e) {
    wx.navigateTo({
      url: '/pages/carts/carts'
    });
  },
  //关注
  toAttension: function (e) {
    wx.navigateTo({
      url: '/pages/users/attension/attension'
    });
  },
  //个人中心
  toUser: function (e) {
    wx.navigateTo({
      url: '/pages/users/users'
    });
  },

  //导航
  layoutCut: function (e) {
    var that = this;
    let id = e.currentTarget.id;
    let storeStatus = this.data.storeStatus;
    let detailsStatus = this.data.detailsStatus;
    let commentStatus = this.data.commentStatus;
    if (id == 1 && storeStatus == false) {
      storeStatus = !storeStatus;
      this.setData({
        storeStatus: storeStatus,
        detailsStatus: false,
        commentStatus: false,
        evaluate: []
      });
    } else if (id == 2 && detailsStatus == false) {
      detailsStatus = !detailsStatus;
      this.setData({
        storeStatus: false,
        detailsStatus: detailsStatus,
        commentStatus: false,
        evaluate: []
      });
      if (!that.data.goodsDesc) {
        this.getDetail(that.data.goodsId);
      }
    } else if (id == 3 && commentStatus == false) {
      commentStatus = !commentStatus;
      this.setData({
        storeStatus: false,
        detailsStatus: false,
        commentStatus: commentStatus,
        evaluate: []
      });
      this.getEvaluate(that.data.goodsId, 0);
    };
  },
  /*跳转到评论模块 */
  backTrackComent: function (e) {
    this.setData({
      moreGoods: 0,
      storeStatus: false,
      detailsStatus: false,
      commentStatus: true,
      evaluate: []
    });
    this.getEvaluate(this.data.goodsId, 0);
  },
  /*跳转到店铺 */
  store: function (e) {
    var shopId = e.currentTarget.dataset.shopid;
    if (shopId == 1) {
      wx.navigateTo({
        url: '/pages/shop-self/shop-self'
      })
    } else {
      wx.navigateTo({
        url: '/pages/shop-home/shop-home?shopId=' + shopId
      })
    }
  },
  /*跳转到店铺 */
  storeGoods: function (e) {
    var shopId = e.currentTarget.dataset.shopid;
    wx.navigateTo({
      url: '/pages/shop-goodslist/shop-goodslist?shopId=' + shopId
    })
  },

  /*参数*/
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
    animation.translateY(600).step();
    this.setData({
      parameterData: animation.export()
    })
    setTimeout(function () {
      animation.translateY(0).step()
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
  /*加入购物车 */
  powerDrawer: function (e) {
    var that = this;
    var statu = e.currentTarget.dataset.statu;
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
    var integralId = that.data.integralId;
    var sessionId = app.globalData.confInfo.sessionId;
    var tokenId = app.globalData.tokenId;
    var num = that.data.num;
    http.Post("addon/integral-carts-weAddCart", { tokenId: tokenId, id: integralId, buyNum: num,sessionId: sessionId, rnd: Math.random() }, function (res) {
      if (res.status == 1) {
        wx.navigateTo({
          url: '/addons/package/pages/integral/settlement/settlement'
        });
        that.util('close');
      } else {
        app.prompt(res.msg);
        that.getData(integralId);
      }
    });
  },
  onRoll: function (e) {
    var that = this;
    var scrollTop = e.detail.scrollTop;
    if (scrollTop > 42) {
      this.setData({ moreGoods: 0 });
    } else {
      this.setData({ moreGoods: 1 });
    }
  },
  //呼叫或进入聊天室
  toCall: function (e) {
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
      var that = this;
      wx.makePhoneCall({
        phoneNumber: that.data.goods.shop.shopTel
      })
    }
  },
  //分享
  onShareAppMessage: function (res) {
    var that = this;
    if (res.from === 'button') {
      // 来自页面内转发按钮
    }
    return {
      title: that.data.goods.goodsName,
      path: '/addons/package/pages/integral/goods/detail?id=' + that.data.integralId
    }
  },
  //评论
  chooseAppraises: function (e) {
    this.setData({
      type: e.currentTarget.dataset.type,
      evaluate: []
    })
    this.getEvaluate(this.data.goodsId, 0);
  },
  onReachBottom: function () {
    var totalPage = this.data.totalPage;
    var page = this.data.page;
    if (page < totalPage) {
      this.getEvaluate(this.data.goodsId, page);
    }
  },
  //预览
  preview: function (e) {
    var that = this;
    var evaluate = that.data.evaluate;
    var domain = app.globalData.domain;
    var id = e.currentTarget.dataset.id;
    var img = e.currentTarget.dataset.img;
    var imgs = [];
    for (var e in evaluate) {
      if (evaluate[e].id == id) {
        for (var i in evaluate[e].images) {
          imgs[i] = app.globalData.resourceDomain + evaluate[e].images[i];
        }
      }
    }
    wx.previewImage({
      current: img,
      urls: imgs
    })
  },
  toLogin:function(){
    wx.navigateTo({
      url: '/pages/login/login'
    })
  }
})