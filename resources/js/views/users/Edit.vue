<template>
    <div>
        <div class="card">
            <div class="card-header">
                <router-link class="text-primary" :to="{ name: 'users.index' }">Users</router-link>
                /
                <span class="text-secondary">Edit User</span>
            </div>
            <div class="card-body">
                <div v-if="ifReady">
                    <form v-on:submit.prevent="updateUser()">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" v-model="name" autocomplete="off" minlength="2" maxlength="255" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" v-model="email" disabled>
                                </div>
                            </div>
                        </div>

                        <br>

                        <router-link class="btn btn-outline-secondary btn-sm" :to="{ name: 'users.index' }"><i class="fas fa-chevron-left"></i>&nbsp; Back</router-link>
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-edit"></i>&nbsp; Update User</button>
                    </form>
                </div>
                <div v-else>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                ifReady: false,
                name: '',
                email: '',
                mobile_number: ''
            };
        },

        mounted() {
            let retrieveUserPromise = new Promise((resolve, reject) => {
                axios.get('/api/users/' + this.$route.params.id).then(res => {
                    this.id            = res.data.user.id;
                    this.name          = res.data.user.name;
                    this.email         = res.data.user.email;

                    resolve();
                });
            });

            Promise.all([retrieveUserPromise]).then(() => {
                this.ifReady = true;
            });
        },

        methods: {
            updateUser() {
                this.ifReady = false;

                let data = {
                    name: this.name
                };

                axios.patch('/api/users/' + this.$route.params.id, data).then(res => {
                    Broadcast.$emit('ToastMessage', {
                        message: 'User Updated Successfully'
                    });

                    this.$router.push({
                        name: 'users.view',
                        params: { id: this.$route.params.id }
                    });
                }).catch(err => {
                    console.log(err);
                });
            }
        }
    }
</script>
