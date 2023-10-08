@extends('master')

@section('title', 'Home')

@section('content2')

<div id="app">
    {{-- main content --}}
    <div class="container">
        <div class="row">
            {{-- images table  --}}
            <div class="col-md-10 col-md-offset-1 imagesTable">
                <h1>Getshare</h1>
                <p>Simply, Just Draw a rectangle and save your tag :)</p>
                <br>
                <h3>Images records</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Tags</th>
                            <th>Image</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($images_records as $key => $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td>{{$item->tags_count}}</td>
                            <td class="imagRow">
                                <img src="{{$item->image}}" alt="{{$item->name}}">
                            </td>
                            <td class="addTagRow" data-toggle="modal" data-target="#imagepopUp"
                                ref="addTag{{$item->id}}"
                                v-on:click="addtag('{{$item->id}}','{{$item->image}}','{{$item->related_tags}}')">
                                <a class="btn">Show<a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            {{-- tags cloud --}}
            <div class="col-md-10 col-md-offset-1">
                <h4>Tags Cloud</h4>
                @foreach ($tags_records as $tag_item)
                <button type="button" class="btn"
                    v-on:click="showTagImg('{{$tag_item->image_id}}')">{{$tag_item->name}}</button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- hidden content --}}
    <div class="container">
        {{-- add new tag form --}}
        <div class="row hidden">
            <form action="/addTag" method="POST" ref="addTagForm_ref">
                <input type="text" v-model="tagName" name="name">
                <input type="number" v-model="ImageID" name="id">
                <input type='text' v-model="coords" name="coords">
                {{ csrf_field() }}
                <input type="submit" value="add">
            </form>
        </div>
        {{-- remoe tag form --}}
        <div class="row hidden">
            <form action="/removeTag" method="POST" ref="removeTagForm_ref">
                <input type="text" v-model="tagID" name="id">
                {{ csrf_field() }}
                <input type="submit" value="Delete">
            </form>
        </div>
        {{-- edit tag form --}}
        <div class="row hidden">
            <form action="/editTag" method="POST" ref="editTagForm_ref">
                <input type="text" v-model="tagID" name="id">
                <input type="text" v-model="tagName" name="name">
                {{ csrf_field() }}
                <input type="submit" value="Edit">
            </form>
        </div>
    </div>

    {{-- popup model with picture and canvas --}}
    <div class="modal fade in" id="imagepopUp" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog"
        aria-hidden="true" style="display: none; padding-right: 6px;">
        <div class="modal-dialog">
            <div>
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="imageCnt" class="drawableImg">
                                <img id="pilt" v-if="!isHidden" v-bind:src="srcImg">
                                <div v-if="tags && !removeTagForm && !tagForm && !editTagForm">
                                    <a class="tagOverImage" v-for="one_tag in tags" :key="one_tag.name"
                                        v-bind:style="{ left: one_tag.x1 + 'px',top:one_tag.y1+'px', minWidth:one_tag.width+'px',minHeight:one_tag.height+'px' }">
                                        @{{one_tag.name }}
                                        <span v-on:click="removeTag(0,one_tag.id)"
                                            class="removeTagBtn glyphicon glyphicon-remove"></span>
                                        <span v-on:click="editTag(0,one_tag.id,one_tag.name)"
                                            class="editTagBtn glyphicon glyphicon-pencil"></span>
                                    </a>
                                </div>
                                {{-- add tag popup --}}
                                <div class="addTagInput" v-if="tagForm">
                                    <input v-bind:class="{ reqInput: dataMissing }" v-model="tagName" 
                                        type="text" placeholder="Tag name">
                                    <br />
                                    <button v-on:click="saveTag" class="btn">Add</button>
                                    <button v-on:click="cancelTag" class="btn">Cancel</button>
                                </div>
                                 {{-- remove tag popup --}}
                                <div class="removeTagConfirm" v-if="removeTagForm">
                                    <p>Are sure to permenantly remove this tag?</p>
                                    <button v-on:click="removeTag(1)" class="btn btn-danger">Yes</button>
                                    <button v-on:click="removeTag(2)" class="btn btn-default">Cancel</button>
                                </div>
                                 {{-- edit tag popup --}}
                                <div class="addTagInput" v-if="editTagForm">
                                    <input v-bind:class="{ reqInput: dataMissing }" v-model="tagName" 
                                        type="text" placeholder="Tag name">
                                    <br />
                                    <button v-on:click="editTag(1)" class="btn">Save</button>
                                    <button v-on:click="editTag(2)" class="btn">Cancel</button>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.0.0/p5.js"></script>
<script src="{{ asset('js/scripts.js') }}"></script>

@endsection