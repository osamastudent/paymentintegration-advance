<div class="messenger-sendCard" id="myapp">
    <form id="message-form" method="POST" action="{{ route('send.message') }}" enctype="multipart/form-data">
        @csrf
        <label><span class="fas fa-plus-circle"></span><input disabled='disabled' type="file" class="upload-attachment" name="file" accept=".{{implode(', .',config('chatify.attachments.allowed_images'))}}, .{{implode(', .',config('chatify.attachments.allowed_files'))}}" /></label>
        <button class="emoji-button"></span><span class="fas fa-smile"></button>
        <textarea readonly='readonly' name="message" class="m-send app-scroll" placeholder="Type a message.."></textarea>
        <button disabled='disabled' class="send-button"><span class="fas fa-paper-plane"></span></button>
    </form>
</div>



<div class="messenger-sendCard d-none" id="myappupdate">
    <h6>update</h6>
    <form id="message-form" method="POST" action="{{ route('update.message') }}" enctype="multipart/form-data">
        @csrf
        <input type="text" name="myuppdateId"  id="myuppdateId" >
        <label><span class="fas fa-plus-circle"></span><input disabled='disabled' type="file" class="upload-attachment" name="file" accept=".{{implode(', .',config('chatify.attachments.allowed_images'))}}, .{{implode(', .',config('chatify.attachments.allowed_files'))}}" /></label>
        <button class="emoji-button"></span><span class="fas fa-smile"></button>
        <textarea readonly='readonly' name="message" id="messageId" class="m-send app-scroll" placeholder="Type a message.."></textarea>
        <button disabled='disabled' id="updateBtn" class="send-button">Update</button>
    </form>
</div>
