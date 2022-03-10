{include common/header@ebcms/admin}
<script>
    var EBCMS = {};
    $(function() {
        EBCMS.state = 0;
        EBCMS.stop = function(message) {
            EBCMS.console(message, 'red');
            EBCMS.console("完毕<hr>");
            EBCMS.state = 0;
            $("#handler").removeClass('btn-warning').addClass('btn-primary').html('一键升级');
        };
        EBCMS.handler = function() {
            if (EBCMS.state) {
                EBCMS.state = 0;
                EBCMS.console("正在终止...", 'red');
            } else {
                if (confirm('升级前请做备份，立即升级吗？')) {
                    EBCMS.state = 1;
                    $("#handler").removeClass('btn-primary').addClass('btn-warning').html('一键终止');
                    EBCMS.check();
                }
            }
        }
        EBCMS.check = function() {
            EBCMS.console("版本检测...");
            $.ajax({
                type: "GET",
                url: "{$router->build('/ebcms/server/check')}",
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        if (!EBCMS.state) {
                            EBCMS.stop('已终止(检测完毕)');
                            return;
                        }
                        EBCMS.console(response.message);
                        EBCMS.source();
                    } else {
                        EBCMS.stop(response.message);
                    }
                },
                error: function(context) {
                    EBCMS.stop("发生错误：" + context.statusText);
                }
            });
        };
        EBCMS.source = function() {
            EBCMS.console("获取资源信息...");
            $.ajax({
                type: "GET",
                url: "{$router->build('/ebcms/server/source')}",
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        if (!EBCMS.state) {
                            EBCMS.stop('已终止(资源信息获取完毕)');
                            return;
                        }
                        EBCMS.console(response.message);
                        EBCMS.download();
                    } else {
                        EBCMS.stop(response.message);
                    }
                },
                error: function(context) {
                    EBCMS.stop("发生错误：" + context.statusText);
                }
            });
        };
        EBCMS.download = function() {
            EBCMS.console("开始下载~");
            $.ajax({
                type: "GET",
                url: "{$router->build('/ebcms/server/download')}",
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        if (!EBCMS.state) {
                            EBCMS.stop('已终止(下载完毕)');
                            return;
                        }
                        EBCMS.console(response.message);
                        EBCMS.backup();
                    } else {
                        EBCMS.stop(response.message);
                    }
                },
                error: function(context) {
                    EBCMS.stop("发生错误：" + context.statusText);
                }
            });
        };
        EBCMS.backup = function() {
            EBCMS.console("程序备份中...");
            $.ajax({
                type: "GET",
                url: "{$router->build('/ebcms/server/backup')}",
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        if (!EBCMS.state) {
                            EBCMS.stop('已终止(程序备份完成)');
                            return;
                        }
                        EBCMS.console(response.message);
                        EBCMS.cover();
                    } else {
                        EBCMS.stop(response.message);
                    }
                },
                error: function(context) {
                    EBCMS.stop("发生错误：" + context.statusText);
                }
            });
        };
        EBCMS.cover = function() {
            EBCMS.console("程序升级中...");
            $.ajax({
                type: "GET",
                url: "{$router->build('/ebcms/server/cover')}",
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        EBCMS.console("程序升级完毕");
                        EBCMS.install();
                    } else {
                        EBCMS.rollback(response.message);
                    }
                },
                error: function(context) {
                    EBCMS.rollback("发生错误：" + context.statusText);
                }
            });
        };
        EBCMS.install = function() {
            EBCMS.console("数据升级中...");
            $.ajax({
                type: "GET",
                url: "{$router->build('/ebcms/server/install')}",
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        if (!EBCMS.state) {
                            EBCMS.stop('系统升级完成');
                            return;
                        }
                        EBCMS.console("数据升级完毕");
                        EBCMS.console("升级成功！");
                        EBCMS.console(response.message + "<hr>尝试继续升级...<hr>");
                        EBCMS.check();
                    } else {
                        EBCMS.rollback(response.message);
                    }
                },
                error: function(context) {
                    EBCMS.rollback("发生错误：" + context.statusText);
                }
            });
        };
        EBCMS.rollback = function(msg) {
            EBCMS.console(msg, 'red');
            EBCMS.console("还原中...");
            $.ajax({
                type: "GET",
                url: "{$router->build('/ebcms/server/rollback')}",
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        EBCMS.stop(response.message);
                    } else {
                        if (confirm('还原失败，继续尝试还原吗？')) {
                            EBCMS.rollback(response.message);
                        } else {
                            EBCMS.stop('还原终止，请手动处理！');
                        }
                    }
                },
                error: function(context) {
                    if (confirm('还原失败，继续尝试还原吗？')) {
                        EBCMS.rollback("发生错误：" + context.statusText);
                    } else {
                        EBCMS.stop('还原终止，请手动处理！');
                    }
                }
            });
        };
        EBCMS.console = function(message, color) {
            $(".console").append("<div style=\"color:" + (color ? color : 'white') + "\">[" + (new Date()).toLocaleString() + "] " + message + "</div>");
            $(".console").scrollTop(99999999);
        }
    });
</script>
<div class="container">
    <div class="my-4 display-4">系统升级</div>
    <div class="my-4">
        <button class="btn btn-primary" onclick="EBCMS.handler();" id="handler">一键升级</button>
        <button class="btn btn-secondary ms-2" onclick="$('.console').html('')">清理日志</button>
    </div>
    <div class="alert alert-primary alert-dismissible fade show" id="tips" role="alert">
        <div class="notice">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div id="progress_main" class="my-4">
        <div class="version"></div>
        <style>
            .console {
                background-color: #000;
                height: 300px;
                width: 100%;
                overflow-y: auto;
            }
        </style>
        <div class="console mt-3 p-2 text-white">
        </div>
    </div>
</div>
<script>
    $(function() {
        $.ajax({
            type: "GET",
            url: "{$router->build('/ebcms/server/check')}",
            dataType: "json",
            success: function(response) {
                $("#tips .notice").html(response.message);
            }
        });
    });
</script>
{include common/footer@ebcms/admin}