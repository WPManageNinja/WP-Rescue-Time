<template>
    <div>
        <h1>User Profile</h1>
        <div v-if="user">
            Name: {{ user.display_name }}<br />
            Email: {{ user.user_email }}<br />
            Registered At: {{ user.user_registered }}<br />
            Rescue User: {{ user.has_rescue_time }}<br />
        </div>
        <div  v-loading.body="loading" class="row">
            <h3>Top Working Categories</h3>
            <el-table :data="stat" border :style="'width: 100%'">
                <el-table-column label="Category" prop="title"></el-table-column>
                <el-table-column label="Time ( Hours )" prop="value"></el-table-column>
            </el-table>
            <h3 v-show="total_logged">Total Logged: {{ total_logged }} hours</h3>
        </div>
            

        
    </div>
</template>
<script>
    export default {
        name: 'UserHome',
        data() {
            return {
                loading: false,
                userId: this.$route.params.user_id,
                user: null,
                stat: null
            }
        },
        computed: {
            total_logged() {
                if(this.stat) {
                    let total = 0;
                    this.stat.forEach((val) => {
                        total += val.value;
                    });
                    return total.toFixed(2);
                }
                return null;
            }  
        },
        methods: {
            getUser() {
                let data = {
                    action: 'wp-rescue-time-ajax_admin',
                    action_route: 'get-user-profile',
                    user_id: this.userId
                };
                jQuery.get(ajaxurl, data)
                    .then(response => {
                        this.user = response.user
                    })
                    .fail( error => {
                        console.log(error);
                    } );
            },
            getDailyStat() {
                this.loading = true;
                let data = {
                    action: 'wp-rescue-time-ajax_admin',
                    action_route: 'get-daily-rescue-stat',
                    user_id: this.userId
                };
                jQuery.get(ajaxurl, data)
                    .then(response => {
                        this.stat = response.stat
                    })
                    .fail( error => {
                        console.log(error);
                    } )
                    .always(() => {
                        this.loading = false;
                    })
            }
        },
        mounted() {
            this.getUser();
            this.getDailyStat();
        }
    }
</script>