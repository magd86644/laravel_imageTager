
var recStart;
var coords_start = {};
var coords_end = {};

var vue_root = new Vue({
    el: '#app',
    data: {
        srcImg: "",
        tagName: "",
        ImageID: 0,
        coords: "",
        tags: null,
        isHidden: true,
        tagForm: false,
        removeTagForm: false,
        editTagForm :false,
        width: 0,
        height: 0,
        dataMissing: false,
        tagID: 0,
    },
    methods: {
        addtag: function (imageId, imagelink, relatedTags) {
            this.tags = null;
            this.tagName = "";
            this.removeTagForm = false;
            this.editTagForm = false;
            this.tagID = 0;
            if (relatedTags != 0) {
                let relatedTga_arr = JSON.parse(relatedTags);
                this.tags = relatedTga_arr;
            }

            this.dataMissing = false;
            this.ImageID = imageId;
            this.tagForm = false;
            var temp_image = new Image();
            temp_image.src = imagelink;
            temp_image.onload = function () {
                var canvas = createCanvas(temp_image.width, temp_image.height);
                canvas.parent('imageCnt');
            }
            this.width = temp_image.width;
            this.height = temp_image.height;
            this.isHidden = false;
            this.srcImg = imagelink;
        },
        cancelTag: function () {
            vue_root.tagForm = false;
            clear();
        },
        saveTag: function () {
            if (this.tagName == '') {
                this.dataMissing = true;
            } else {
                vue_root.tagForm = false;
                if ((vue_root.ImageID != 0) && (vue_root.coords != '')) {
                    vue_root.$refs.addTagForm_ref.submit();
                }
                clear();
            }
        },
        showTagImg: function (Image_ref) {
            var x = 'addTag' + Image_ref;
            vue_root.$refs[x].click();
        },
        removeTag: function (action, id) {
            recStart = false;	// stop draw()
            switch (action) {
                case 0://show confirm form
                    vue_root.tagID = id;
                    vue_root.removeTagForm = true;
                    clear();
                    break;
                case 1://confirmed, delete
                    vue_root.$refs.removeTagForm_ref.submit();
                    break;
                case 2://cancel
                    vue_root.removeTagForm = false;
                    break;
                default:
                    break;
            }
            
        },
        editTag: function(action,id,name){
            recStart = false;	// stop draw()
              switch (action) {
                case 0://show confirm form
                    vue_root.tagID = id;
                    vue_root.tagName = name;
                    vue_root.editTagForm = true;
                    clear();
                    break;
                case 1://confirmed, delete
                    vue_root.$refs.editTagForm_ref.submit();
                    break;
                case 2://cancel
                    vue_root.editTagForm = false;
                    break;
                default:
                    break;
            }
        }
    }
})



function draw() {
    if (recStart)
        drawRect();
}

function drawRect() {
    clear();
    noFill();
    stroke('red');
    rect(coords_start.x, coords_start.y, mouseX - coords_start.x, mouseY - coords_start.y);
}


mouseClicked = function (e) {
    console.log('mouse clicked');
    if (e.target.id == 'defaultCanvas0') { //click inside image
        if ((mouseButton === LEFT) && (!vue_root.isHidden) && (!vue_root.tagForm) && (!vue_root.removeTagForm)) {
            if (!recStart) {			// start rectangle, give initial coords
                coords_start.x = mouseX;
                coords_start.y = mouseY;
                recStart = true;	// draw() starts to draw
            } else {
                coords_end.x = mouseX;
                coords_end.y = mouseY;
                recStart = false;	// stop draw()
                drawRect();			// draw final rectangle
                vue_root.tagForm = true;
                let temp_arr = { start: coords_start, end: coords_end };
                vue_root.coords = JSON.stringify(temp_arr);
            }
        }
    }

};