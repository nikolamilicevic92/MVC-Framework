
<div id="RTE">
  <!-- <div class="editor-options">
    <div>
      <button onclick="bold()" title="Bold"><b>B</b></button>
      <button onclick="italic()" title="Italic">I</button>
      <button onclick="horizontalRule()" title="Horizontal rule">HR</button>
      <button onclick="underline()" title="Underline">U</button>
      <button onclick="strikeThrough()" title="Strike through">S</button>
      <button onclick="superscript()" title="Superscript">Sup</button>
      <button onclick="subscript()" title="Subscript">Sub</button>
      <select onchange="font(this.value)" onfocus="this.selectedIndex=-1">
        <option value="Arial">Arial</option>
        <option value="Helvetica">Helvetica</option>
        <option value="Times New Roman">Times New Roman</option>
        <option value="Times">Times</option>
        <option value="Courier New">Courier New</option>
        <option value="Courier">Courier</option>
        <option value="Verdana">Verdana</option>
        <option value="Georgia">Georgia</option>
        <option value="Palatino">Palatino</option>
        <option value="Garamond">Garamond</option>
        <option value="Bookman">Bookman</option>
        <option value="Comic Sans MS">Comic Sans MS</option>
        <option value="Trebuchet MS">Trebuchet MS</option>
        <option value="Arial Black">Arial Black</option>
        <option value="Impact">Impact</option>
      </select>
      <select onchange="fontSize(this.value)" onfocus="this.selectedIndex=-1">
        <option value="1">Font size 1</option>
        <option value="2">Font size 2</option>
        <option value="3">Font size 3</option>
        <option value="4">Font size 4</option>
        <option value="5">Font size 5</option>
        <option value="6">Font size 6</option>
        <option value="7">Font size 7</option>
      </select>
      <select onchange="heading(this.value)" onfocus="this.selectedIndex=-1">
        <option value="H1"><h1>Heading 1</h1></option>
        <option value="H2"><h2>Heading 2</h2></option>
        <option value="H3"><h3>Heading 3</h3></option>
        <option value="H4"><h4>Heading 4</h4></option>
        <option value="H5"><h5>Heading 5</h5></option>
        <option value="H6"><h6>Heading 6</h6></option>
      </select>
      <button onclick="alignCenter()" title="Align center">Center</button>
      <button onclick="alignRight()" title="Align right">Right</button>
      <button onclick="alignLeft()" title="Align left">Left</button>
      <button onclick="justify()" title="Justify">Justify</button>
    </div>
    <div>
      <label>Background</label>
      <input type="color" onchange="backgroundColor(this.value)" title="Background color">
      <label>Color</label>
      <input type="color" onchange="color(this.value)" title="Color">
      <button onclick="orderedList()" title="Ordered list">OL</button>
      <button onclick="unorderedList()" title="Unordered list">UL</button>
      <button onclick="showLinkPrompt()" title="Link">Link</button>
      <button onclick="showImagePrompt()" title="Image">Image</button>
      <button onclick="showVideoPrompt()" title="video">Video</button>
    </div>
  </div>

  <iframe></iframe> -->


  <!-- <div class="prompt link-prompt d-none">
    <div>
      <input type="text" id="linkURL" class="form-control" placeholder="URL...">
    </div>
    <div>
      <input type="text" id="linkText" class="form-control" placeholder="Text...">
    </div>
    <div>
      <input type="checkbox" id="linkTarget">
      <label>Open in new tab</label>
    </div>
    <div class="clearfix">
      <button onclick="hideLinkPrompt()" class="btn dark">Cancel</button>
      <button onclick="link()" class="btn dark">Insert</button>
    </div>
  </div> -->

  <!-- <div class="prompt image-prompt d-none">
    <div>
      <input type="text" id="imageSrc" class="form-control" placeholder="Source..." value="http://via.placeholder.com/350x150">
    </div>
    <div>
      <input type="text" id="imageAlt" class="form-control" placeholder="Alt...">
    </div>
    <div>
      <label>Width</label>
      <input type="number" style="width:92px;" id="imageWidth" class="form-control inline">
      <label>Height</label>
      <input type="number" style="width:92px;" id="imageHeight" class="form-control inline">
    </div>
    <div>
      <input type="text" id="imageClass" class="form-control" placeholder="Class...">
    </div>
    <div>
      <input type="text" id="imageId" class="form-control" placeholder="Id...">
    </div>
    <div>
      <label>Float left</label>
      <input type="radio" name="imageFloat" id="imageFloatLeft">
      <label>Float right</label>
      <input type="radio" name="imageFloat" id="imageFloatRight">
    </div>
    <div class="clearfix">
      <button onclick="hideImagePrompt()" class="btn dark">Cancel</button>
      <button onclick="image()" class="btn dark">Insert</button>
    </div>
  </div> -->

  <div class="prompt video-prompt d-none">
    <div>
      <input type="text" id="videoSrc" class="form-control" placeholder="Source...">
    </div>
    <div>
      <label>Width</label>
      <input type="number" style="width:92px;" class="form-control inline" id="videoWidth">
      <label>Height</label>
      <input type="number" style="width:92px;" class="form-control inline" id="videoHeight">
    </div>
    <div>
      <input type="text" class="form-control" id="videoClass" placeholder="Class...">
    </div>
    <div>
      <input type="text" id="videoId" class="form-control" placeholder="Id...">
    </div>
    <div>
      <label>Controls</label>
      <input type="checkbox" id="videoControls">
      <label>Autoplay</label>
      <input type="checkbox" id="videoAutoplay">
      <label>Loop</label>
      <input type="checkbox" id="videoLoop">
    </div>
    <div>
      <label>Float left</label>
      <input type="radio" name="videoFloat" id="videoFloatLeft">
      <label>Float right</label>
      <input type="radio" name="videoFloat" id="videoFloatRight">
    </div>
    <div class="clearfix">
      <button onclick="hideVideoPrompt()" class="btn dark">Cancel</button>
      <button onclick="_video()" class="btn dark">Insert</button>
    </div>
  </div>
</div>