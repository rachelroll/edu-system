@extends('layout.layout')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
@endsection

@section('content')
    <div class="container">
        <div class="no-gutters" style="width: 960px; margin:0 auto">
            <h4 class="text-muted text-center">专心写文章</h4>
            <br>
            <form method="post" action="{{ route('web.posts.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input name="title" type="text" class="form-control" placeholder="标题">
                </div>
                <div class="form-group">
                    <input name="description" type="text" class="form-control" placeholder="一句话简介">
                    <small class="form-text text-muted">将会展示在列表页, 所以想一句最有吸引力的话吧</small>
                </div>
                <div class="form-group">
                    <textarea name="content" id="editor" data-autosave="editor-content" autofocus></textarea>
                    <div id="preview" class="editor-style"></div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="custom-file">
                            <input name="cover" type="file" class="custom-file-input" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04">
                            <label class="custom-file-label" for="inputGroupFile04">封面图</label>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon04">Button</button>
                        </div>
                    </div>
                    <small class="form-text text-muted">选一张门面图吧, 会展示在首页哦!</small>
                </div>
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">¥</span>
                        </div>
                        <input type="text" name="price" class="form-control" aria-label="Amount (to the nearest dollar)">
                        <div class="input-group-append">
                            <span class="input-group-text">元</span>
                        </div>
                    </div>
                    <small class="form-text text-muted">我们支持知识有价! 也支持知识无价, 定价 0 元也是可以的哦~~~</small>
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
    @stop



@section('js')
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

    {{--<script src="../js/codemirror-4.inline-attachment.min.js"></script>--}}
    {{--<script src="../js/inline-attachment.min.js"></script>--}}
    {{--<script src="../js/input.inline-attachment.js"></script>--}}


    <script>
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
                link: ["[", "](http://)"],
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
            // showIcons: ["code", "table"],
            toolbar: {
                image: {
                    action: null,
                    className: 'md-upload-img fa fa-picture-o',
                    whenEleCreate: function (el) {
                        let self = this;
                        // add custom class
                        el.classList.add('md-upload-img');
                        // append input element
                        let inputEle = document.createElement('input');
                        inputEle.setAttribute('type', 'file');
                        inputEle.setAttribute('multiple', true);
                        inputEle.setAttribute('accept', 'image/*');
                        el.appendChild(inputEle);

                        inputEle.onchange = (evt) => {
                            let imgs = evt.currentTarget.files;
                            if (imgs.length) {
                                let xhr = new window.XMLHttpRequest();
                                let formData = new window.FormData();
                                for (let i = 0; i < imgs.length; i++) {
                                    formData.append('upload_img_' + i, imgs[i])
                                }
                                xhr.open('POST', 'http://localhost:3000/upload', true);
                                xhr.onload = (event) => {
                                    if (xhr.status === 200) {
                                        let cm = self.codemirror;
                                        let stat = self.getState();
                                        let options = self.options;
                                        let res = JSON.parse(event.target.response);
                                        let urls = res.urls;

                                        urls.forEach((url) => {
                                            self.replaceSelection(cm, stat.iamge, options.insertTexts.image, url)
                                        })
                                    } else {
                                        console.log('fail')
                                    }
                                }
                                xhr.send(formData)
                            }
                        }

                        return el
                    }
                }
            }
        });

        // inlineAttachment.editors.input.attachToInput(document.getElementById("editor"));

    </script>
    @endsection