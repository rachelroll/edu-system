@extends('layout.layout')


@section('css')
    @endsection

@section('content')
    <div class="container">
        <div class="no-gutters" style="width: 960px; margin:0 auto">
            <h4 class="text-muted text-center">专心写文章</h4>
            <br>
            <form method="post" action="{{ route('comments.store') }}">
                @csrf
                <div class="form-group">
                    <input name="title" type="text" class="form-control" placeholder="标题">
                </div>
                <div class="form-group">
                    <input name="description" type="text" class="form-control" placeholder="一句话简介">
                    <small class="form-text text-muted">将会展示在列表页, 所以想一句最有吸引力的话吧</small>
                </div>
                <div class="form-group">
                    <textarea name="content" id="editor" placeholder="支持 Markdown 编写" autofocus></textarea>
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
    <script>
        $(function() {
            var editor = new Simditor({
                textarea: $('#editor'),
                //optional options
                // toolbarFloat:true,
                // placeholder: '',
            });
        })
    </script>
    @endsection