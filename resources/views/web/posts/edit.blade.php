@extends('layout.layout')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('css')
    {{--markdown 富文本编辑器--}}
    <link href="https://cdn.bootcss.com/simplemde/1.11.2/simplemde.min.css" rel="stylesheet">
    {{--图片裁切插件--}}
    <link href="https://cdn.bootcss.com/cropperjs/2.0.0-alpha/cropper.css" rel="stylesheet">
    <style>
        /*确保 markdown 编辑器全屏时不被导航栏遮挡, 很重要, 不能删*/
        .editor-toolbar.fullscreen {
            z-index: 1020;
        }

        #image {
            max-width: 100%; /* This rule is very important, please do not ignore this! */
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="no-gutters" style="width: 960px; margin:0 auto">
            <h4 class="text-muted text-center">编辑 {{ $post->title }}</h4>
            <br>
            <form method="post" action="{{ route('web.posts.update', ['id' => $post->id]) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input name="title" type="text" class="form-control" placeholder="标题" value="{{ $post->title }}">
                </div>
                <div class="form-group">
                    <input name="description" type="text" class="form-control" placeholder="一句话简介" value="{{ $post->description }}}">
                    <small class="form-text text-muted">将会展示在列表页, 所以想一句最有吸引力的话吧</small>
                </div>
                <div class="form-group">
                    <textarea name="content" id="editor" data-autosave="editor-content" autofocus>{{ $post->content }}</textarea>
                    <div id="preview" class="editor-style"></div>
                </div>

                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">¥</span>
                        </div>
                        <input type="text" name="price" class="form-control" aria-label="Amount (to the nearest dollar)" value="{{ $post->price }}">
                        <div class="input-group-append">
                            <span class="input-group-text">元</span>
                        </div>
                    </div>
                    <small class="form-text text-muted">我们支持知识有价! 也支持知识无价, 定价 0 元也是可以的哦~~~</small>
                </div>
                <div id="images">
                    <input type="file" name="image" id="image" onchange="readURL(this);"/>
                    <br>
                    {{--放置用户选择的照片--}}
                    <div id="image_container" class="pt-2 pb-2" style="width: 300px;">
                        {{--放置用户上传照片的 img 标签--}}
                        {{--<img id="blah" src="#" alt="your image" class="d-none" style="width: 50px" />--}}
                    </div>
                    {{--裁切后的图片展示--}}
                    <div id="cropped_result" class="pt-2 pb-2" style="width: 300px">
                    </div>

                    <input type="hidden" id="data" name="data">

                    {{--点击触发裁切事件--}}
                    <button id="crop_button" class="d-none btn btn-outline-primary mr-1 btn-sm pl-3 pr-3 mb-3">裁切</button>
                </div>
                <div class="btn-toolbar">
                    <button type="submit" class="btn btn-primary mr-1 btn-sm pl-3 pr-3">发布文章</button>
                    <button type="button" class="btn btn-light">or</button>
                    <button type="submit" class="btn btn-secondary ml-1 btn-sm pl-3 pr-3">保存草稿</button>
                </div>
            </form>

            <br>
            <br>
        </div>
    </div>
    <br>
    <br>
@stop

@section('js')
    {{--markdown 的 JS 解析器--}}
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    {{--markdown 编辑器--}}
    <script src="https://cdn.bootcss.com/simplemde/1.11.2/simplemde.min.js"></script>
    <script src="../js/dropzone.js"></script>
    <script src="https://cdn.bootcss.com/cropperjs/2.0.0-alpha/cropper.js"></script>
    <script>
        // markdown 编辑器配置
        var simplemde = new SimpleMDE({
            // 对应 textarea 输入框
            element: $("#editor")[0],
            // 自动聚焦到输入框
            autofocus: true,
            // 自动保存
            autosave: {
                enabled: true,
                uniqueId: "#editor",
                delay: 1000,
            },
            blockStyles: {
                bold: "__",
                italic: "_"
            },
            forceSync: true,
            hideIcons: ["guide", "heading"],
            indentWithTabs: false,
            insertTexts: {
                horizontalRule: ["", "\n\n-----\n\n"],
                image: ["![](http://", ")"],
                // link: ["[", "](http://)"],
                table: ["", "\n\n| Column 1 | Column 2 | Column 3 |\n| -------- | -------- | -------- |\n| Text     | Text      | Text     |\n\n"],
            },
            parsingConfig: {
                allowAtxHeaderWithoutSpace: true,
                strikethrough: false,
                underscoresBreakWords: true,
            },
            placeholder: "支持 Markdown 编写",
            previewRender: function(plainText, preview) { // Returns HTML from a custom parser, Async method
                setTimeout(function(){
                    preview.innerHTML = marked(plainText);
                }, 250);
                return "预览生成中......";
            },
            // 用 highlight.js 使代码高亮, 仅预览时生效
            renderingConfig: {
                codeSyntaxHighlighting: true,
            },
            promptURLs:true,
            showIcons: ["code", "table", "clean-block", "link", "horizontal-rule", "side-by-side", "fullscreen", "heading-1", "heading-2", "|", "heading-3", "|"],
        });

    </script>

    <script type="text/javascript" defer>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                console.log(input.files[0], input.files);
                var reader = new FileReader();
                reader.onload = function (e) {
                    // 在上传新图之前, 先把原来的图片删掉
                    $('#blah').remove();
                    // 在上传新图之前, 先把原来生成的裁切 div 删掉
                    $('.cropper-container').remove();


                    // 创建一个新的 img 标签
                    var img = $('<img id="blah">');

                    // 给新创建的 img 标签添加 src 属性
                    img.attr('src', e.target.result).width('300px');
                    img.appendTo('#image_container');
                };
                reader.readAsDataURL(input.files[0]);
                // 用定时器调用裁切函数
                setTimeout(initCropper, 1000);
                // 显示 "裁切" 按钮
                $('#crop_button').addClass('d-block');
            }
        }
        function initCropper(){
            var image = document.getElementById('blah');
            var cropper = new Cropper(image, {
                // 设置图片裁切的比例
                aspectRatio: 34 / 21,
                // 去掉栅格背景
                background: false,
                // 去掉深灰色背景
                modal: false,
                viewMode: 2,
            });

            // 点击裁切按钮的点击事件
            document.getElementById('crop_button').addEventListener('click', function(e){
                // 把裁切好的图片生成 url, 同时设置图片显示的宽度
                var imgurl =  cropper.getCroppedCanvas({width: 200}).toDataURL("image/png");
                console.log(imgurl);
                // 创建一个新的 img 标签
                var img = document.createElement("img");
                // 给新创建的 img 标签添加 src 属性
                img.src = imgurl;

                // 把裁切好的 img 标签放到页面上
                document.getElementById("cropped_result").appendChild(img);
                // 把裁切好的图片地址放到表单中
                $('#data').val(imgurl);
                // 点击 button 标签会自动提交整个表单, 所以这里要阻止表单提交
                e.preventDefault();
                // 裁切之后, 把 "裁切" 按钮隐藏
                $('#crop_button').removeClass('d-block');
                // 裁切后删除原图
                $('#image_container').addClass('d-none');
            })
        }
    </script>
@endsection