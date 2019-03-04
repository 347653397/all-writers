<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * redis keys
 */

$config["redis_keys"] = [
    //评论点赞或取消
    'commentPraise' => 'comment_praise',
    //播放量前缀
    'playPrex' => 'COURSE_ITEM_PAYNUM_',
    //评论被打赏
    'commentToReward' => 'comment_to_reward_',
    //音频被打赏
    'audioToReward' => 'audio_to_reward_',
    //评论打赏有序集合 用于做最热评论
    'rankCommentMoney' => 'RANK_COMMENT_MONEY_',

    //红点-我的钱包
    'walletDotPrex' => 'wallet_dot',
    //红点-我的订单
    'orderDotPrex' => 'order_dot',
    //红点-我的评论-回复
    'commentDotPrex' => 'comment_dot',
    //红点-我的投稿
    'contributeDotPrex' => 'contribute_dot',
    //红点-我的拍卖
    'auctionDotPrex' => 'auction_dot'

];