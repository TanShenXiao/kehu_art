// pages/news/news-detail.js
var http = require('../../utils/request.js');
var parse = require('../common/parse/parse.js');
Page({
  /**
   * 页面的初始数据
   */
  data: {
    id:0,
    likeStatus: 0,
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      id:options.id
    });
    this.getNewDetail();
  },
  getNewDetail: function (e) {
    var that = this;
    http.Post('weapp/news/geturlNews', { id: that.data.id }, function (res) {
      if (res.status == 1) {
        var articleContent = res.data.articleContent;
        if (articleContent) {
          parse.wxParse('articleContent', 'html', articleContent, that);
        }
        let i = 0;
        that.setData({
          articleTitle: res.data.articleTitle,
          likeNum: res.data.likeNum,
          createTime: res.data.createTime,
          articleId: res.data.articleId
        })
        that.actionLike(res.data.articleId);

      }
    })
  },
  actionLike(e) {
    var that = this;
    var newsId = '';
    var newsIds = [];
    if (typeof (e) == 'number') {
      var newsId = e;
      var status = 0;
    } else {
      var newsId = e.currentTarget.dataset.newsid;
      var status = 1;
    }
    var i = 0;
    wx.getStorage({
      key: 'articleId',
      success: function (cache) {
        var data = cache.data;
        that.setData({
          newsIds: data
        })
        if (data.length != 0) {
          for (i; i < data.length; i++) {
            if (newsId == data[i]) {
              var has = true;
            }
          }
          if (has != true && status == 1) {
            that.like(newsId);
          } else if (has != true && status == 0) {
            that.setData({
              likeStatus: 0
            })
          } else if (has == true && status == 0) {
            that.setData({
              likeStatus: 1,
              likeNum: that.data.likeNum
            })
          }
        } else {
          status == 1 ? that.like(newsId) : null;
        }
      },
      fail: function () {
        wx.setStorage({
          key: "articleId",
          data: [],
          success: function () {

          }
        })
      }
    })
  },
  like: function (newsId) {
    var that = this;
    http.Post('weapp/news/like', { id: newsId }, function (res) {
      if (res.status == 1) {
        that.setData({
          likeNum: that.data.likeNum + 1,
          likeStatus: 1
        })
        that.data.newsIds.splice(0, 0, newsId)
        wx.setStorage({
          key: "articleId",
          data: that.data.newsIds
        })
      }
    })
  },
})