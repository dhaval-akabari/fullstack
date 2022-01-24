<template>
  <div>
    <!-- Page Heading -->
      <h1 class="h3 mb-2 text-gray-800">Assign Permission</h1>
      
      <!-- DataTales Example -->
      <div class="card shadow mb-4">
          <div class="card-header py-3 text-right">
            <select class="form-control w-25" v-model="data.id" @change.prevent="changePermission">
                <option :value="null" disabled>Select User Role</option>
                <option v-for="(role, i) in roles" :key="i" :value="role.id">{{ role.roleName }}</option>
            </select>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                          <tr>
                              <th>Resource Name</th>
                              <th>Read</th>
                              <th>Write</th>
                              <th>Update</th>
                              <th>Delete</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="(resource, index) in resources" :key="index">
                              <td>{{ resource.resourceName }}</td>
                              <td><input type="checkbox" v-model="resource.read"></td>
                              <td><input type="checkbox" v-model="resource.write"></td>
                              <td><input type="checkbox" v-model="resource.update"></td>
                              <td><input type="checkbox" v-model="resource.delete"></td>
                          </tr>
                      </tbody>
                  </table>
              </div>
                <div>
                    <button type="button" @click.prevent="assignPermission" class="btn btn-primary">Save Changes</button>
                </div>
          </div>
      </div>
  </div>
</template>

<script>
export default {
    data()  {
        return {
            data: {
                roleName: '',
                id: null,
            },
            roles: [],
            resources: [
                { resourceName: 'Dashboard', read: false, write: false, update: false, delete: false, name: 'dashboard', path: '/dashboard' },
                { resourceName: 'Tag', read: false, write: false, update: false, delete: false, name: 'tag', path: '/tag' },
                { resourceName: 'Category', read: false, write: false, update: false, delete: false, name: 'category', path: '/category' },
                { resourceName: 'Create Blog', read: false, write: false, update: false, delete: false, name: 'create-blog', path: '/create-blog' },
                { resourceName: 'All Blogs', read: false, write: false, update: false, delete: false, name: 'blogs', path: '/blogs' },
                { resourceName: 'Admin User', read: false, write: false, update: false, delete: false, name: 'admin-user', path: '/admin-user' },
                { resourceName: 'Role', read: false, write: false, update: false, delete: false, name: 'role', path: '/role' },
                { resourceName: 'Assign Role', read: false, write: false, update: false, delete: false, name: 'assign-role', path: '/assign-role' },
            ],
            defaultResources: [],
        }
    },
    created() {
        this.defaultResources = [...this.resources]; // copy old resources.
        this.getRole();
    },
    methods: {
        async getRole() {
            const res = await this.callApi('get', '/admin/role', []);
            if(res.status === 200) {
                this.roles = res.data;
                if(res.data.length) {
                    this.data.id = res.data[0].id;
                    if(res.data[0].permission) {
                        this.resources = JSON.parse(res.data[0].permission);
                    }
                }
            } else {
                this.error('Something went wrong in fetching roles!');
            }
        },
        async assignPermission() {
            let data = JSON.stringify(this.resources);
            const res = await this.callApi('post', '/admin/role/permission', { 'permission': data, 'id': this.data.id });
            if(res.status === 200) {
                this.success('Permission assign successfully!');
                // assign new data
                let index = this.roles.findIndex(role => role.id === this.data.id);
                this.roles[index].permission = data;
                // window.location = '/assign-role';
            } else {
                this.error('Something went wrong');
            }
        },
        async changePermission(e) {
            let index = this.roles.findIndex(role => role.id === this.data.id);
            let permission = this.roles[index].permission;
            if(!permission) {
                this.resources = this.defaultResources;
            } else {
                this.resources = JSON.parse(permission);
            }
        }
    },
}
</script>