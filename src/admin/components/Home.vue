<template>
    <div>
        <h1>Users</h1>

        <el-table
                v-loading.body="loading"
                :data="users"
                border
                :style="'width: 100%'"
        >
            <el-table-column label="Name" prop="display_name"></el-table-column>
            <el-table-column label="Email" prop="user_email"></el-table-column>
            <el-table-column
                    prop="ID"
                    label="Action"
                  >
                <template scope="scope">
                    <router-link :to="{ name: 'user_home', params: { user_id: scope.row.ID } }">View Report</router-link>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script type="text/babel">
    export default {
        name: 'AdminDashBoard',
        data() {
            return {
                users: null
            }
        },
        methods: {
          getUsers() {
              let data = {
                  action: 'wp-rescue-time-ajax_admin',
                  action_route: 'get-rescue-users'
              };
              jQuery.get(ajaxurl, data)
                  .then(response => {
                        this.users = response.users;
                  })
                  .fail(error => {
                      console.log(error);
                  });
          }  
        },
        mounted() {
            this.getUsers();
        }
    }
</script> 

<style>
    
</style>