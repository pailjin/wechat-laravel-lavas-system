/**
 * 小程序配置文件
 */


var host = 'https://api.abc.com';
var qiniudomain = 'https://qinius.abc.com';
var config = {

    // 下面的地址配合云端 Demo 工作
    service: {
        host,
        qiniudomain:qiniudomain,

        // 登录地址，用于建立会话
        loginUrl: `${host}/api/login`,

        // 测试的请求地址
        requestUrl: `${host}/api`,
        // requestUrlmalls: `${host}/api/searchmalls/search`,
        // requestUrlduobiusers: `${host}/api/duobiusers`,
        // 测试的信道服务地址
        tunnelUrl: `${host}/weapp/tunnel`,

        // 上传图片接口,不需要用，直接用七牛
        uploadUrl: `${host}/weapp/upload`,
        downloadUrl:`${qiniudomain}`
    }
};

module.exports = config;
