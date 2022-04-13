<style>
.xd-mfa-remember-toast {
    position: fixed;
    top: 16px;
    right: 16px;
    z-index: 99999;
    background-color: black;
    color: white;
    padding: 1rem;
    border-radius: 5px;
    box-shadow: 0 2px 15px rgb(0 0 0 / 25%);
}
.xd-mfa-remember-toast__message p {
    margin-bottom: .5rem;
}

.xd-mfa-remember-toast__actions {}

.xd-mfa-remember-toast__action {
    padding: .05rem .5rem .15rem .5rem;
    border-radius: 5px;
    background-color: rgba(255,255,255,0);
    margin-right: .5rem;
}

.xd-mfa-remember-toast__action:hover,
.xd-mfa-remember-toast__action:focus {
    background-color: rgba(255,255,255,.25);
}
</style>

<div class="xd-mfa-remember-toast">
    <div class="xd-mfa-remember-toast__message">
        <h6><%t XD\MFARemember\Toast.Title 'Remember browser' %></h6>
        <p><%t XD\MFARemember\Toast.Message 'If you save this browser, you don't have to enter a code when you log in again from this browser.' %></p>
    </div>
    <div class="xd-mfa-remember-toast__actions">
        <a class="xd-mfa-remember-toast__action" href="{$Link('rememberDevice')}">
            <%t XD\MFARemember\Toast.Remember 'Save this browser' %>
        </a>
        <a class="xd-mfa-remember-toast__action" href="{$Link('alwaysAsk')}">
            <%t XD\MFARemember\Toast.AlwaysAsk 'Don't save' %>
        </a>
    </div>
</div>