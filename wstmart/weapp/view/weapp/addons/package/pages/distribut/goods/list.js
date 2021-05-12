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
    goods: [],//商品
    totalCnt: -1,
    currStatus: 0,
    page: 0,
    lastPage: 0,
    scrollLeft: 0,
    orderBy: 0,
    order: 0,
    keyword: '',//搜索关键词
    isShow: false,
    /*排序选择 */
    sortArray: [
      { id: '0', title: '销量', selected: true, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '1', title: '价格', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '2', title: '人气', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '3', title: '上架时间', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
    ],
    screenIcon: '/image/screen.png',
    screenIcon1: '/image/screen2.png',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
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
  /*排序选择 */
  sortSelect(e) {
    var id = e.currentTarget.dataset.id;
    var sortArray = this.data.sortArray;
    var condition = id;
    var conditioned = this.data.condition;
    var desc = this.data.desc;
    if (conditioned != condition) {
      desc = 1;
    }
    if (desc == 0) {
      desc = 1;
    } else if (desc == 1) {
      desc = 0;
    }
    for (let i = 0; i < sortArray.length; i++) {
      if (sortArray[i].id != id) {
        sortArray[i].selected = false;
      };
      if (sortArray[i].id == id) {
        sortArray[i].selected = true;
      };
    };
    this.setData({
      sortArray: sortArray,
      condition: condition,
      desc: desc,
      goods: [],
      page: 0
    });
    this.goodsList();//调用获取数据
  },
  goodsList: function () {
    var that = this;
    var params = {};
    var page = that.data.page + 1;
    params.tokenId = app.globalData.tokenId;
    params.keyword = that.data.keyword;
    params.page = page;
    params.orderBy = that.data.condition;
    params.order = that.data.desc;
    that.setData({ load: 0 });
    http.Post("addon/distribut-goods-weGoodsList", params, function (res) {
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
  }
})