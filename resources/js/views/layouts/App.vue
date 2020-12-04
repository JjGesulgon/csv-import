<template>
    <div>
        <navbar></navbar>
        <div class="container-fluid mb-5">
            <div class="row">
                <div class="col-md-2" style="padding-right: 0px;">
                    <sidebar></sidebar>
                </div>
                <div class="col-md-10">
                    <div class="mt-3">
                        <transition name="fade" mode="out-in">
                            <router-view></router-view>
                        </transition>
                    </div>
                </div>
            </div>
        </div>

        <div id="toast-message" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false" style="position: absolute; bottom: 0; right: 0; margin-right: 30px; margin-bottom: 30px;">
            <div class="toast-header">
                <strong class="mr-auto">System Notification</strong>
                &nbsp; <small class="text-muted">Now</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                {{ message }}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                message: ''
            }
        },

        created() {
            Echo.connector.socket.on('disconnect', (reason) => {
                alert('No Internet Connection');
            });

            Echo.connector.socket.on('reconnect', (reason) => {
                alert('Connected');
            });

            Echo.channel('chat').listen('NewChatMessage', (event) => {
                console.log(event);
            });
        },

        mounted() {
            Broadcast.$on('ToastMessage', (event) => {
                this.message = event.message;
                
                $('#toast-message').toast('show');

                setTimeout(() => {
                    $('#toast-message').toast('hide');
                }, 3000);
            });
        }
    }
</script>
