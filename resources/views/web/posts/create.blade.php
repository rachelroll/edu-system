@extends('layout.layout')
@section('css')
    <link rel="stylesheet" href="../css/simditor-markdown.css" media="screen" charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="assets/styles/simditor.css" />
@endsection

@section('content')
    <div class="container">
        <div class="no-gutters" style="width: 960px; margin:0 auto">
            <h4 class="text-muted text-center">专心写文章</h4>
            <br>
            <form method="post" action="{{ route('posts.store') }}" enctype="multipart/form-data">
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
    <script src="../js/module.js"></script>
    <script src="../js/hotkeys.js"></script>
    <script src="../js/uploader.js"></script>
    <script src="https://cdn.bootcss.com/simditor/2.3.23/lib/simditor.min.js"></script>
    <script src="https://cdn.bootcss.com/marked/0.6.1/marked.min.js"></script>
    <script src="../js/mobilecheck.js"></script>
    <script src="https://cdn.bootcss.com/to-markdown/3.1.1/to-markdown.min.js"></script>
    <script type="text/javascript" src="../js/simditor-markdown.js"></script>


    <script>
        (function() {
            $(function() {
                toolbar = ['title', 'bold', 'italic', 'underline', 'strikethrough', 'fontScale', 'color', '|', 'ol', 'ul', 'blockquote', 'code', 'table', '|', 'link', 'image', 'hr', '|', 'indent', 'outdent', 'alignment', 'markdown'];
                mobileToolbar = ["bold", "underline", "strikethrough", "color", "ul", "ol"];
                if (mobilecheck()) {
                    toolbar = mobileToolbar;
                }
                var editor = new Simditor({
                    textarea: $('#editor'),
                    autosave: 'editor-content',
                    markdown: true,
                    toolbar: toolbar,
                    pasteImage: true,
                    defaultImage: 'assets/images/image.png',
                    upload: location.search === '?upload' ? {
                        url: '/upload'
                    } : false
                });
                $('#editor').attr("data-autosave-confirm", "是否读取上次退出时未保存的草稿？");
                $preview = $('#preview');
                if ($preview.length > 0) {
                    return editor.on('valuechanged', function(e) {
                        return $preview.html(editor.getValue());
                    });
                }
            });
        }).call(this);
    </script>
    @endsection