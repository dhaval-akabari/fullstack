<template>
  <div>
    <!-- Page Heading -->
      <h1 class="h3 mb-2 text-gray-800">Admin User</h1>
      
      <!-- DataTales Example -->
      <div class="card shadow mb-4">
          <div class="card-header py-3 text-right">
            <button v-if="isWritePermitted" class="btn btn-secondary btn-icon-split btn-sm" @click.prevent="showAddModal">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Add user</span>
            </button>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                          <tr>
                              <th>Name</th>
                              <th>Email</th>
                              <th>User Role</th>
                              <th>Created At</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody v-if="users.length > 0">
                          <tr v-for="(user, index) in users" :key="index">
                              <td class="text-dark font-weight-bold">{{ user.fullName }}</td>
                              <td>{{ user.email }}</td>
                              <td>{{ user.role_id }}</td>
                              <td>{{ formatDate(user.created_at) }}</td>
                              <td>
                                <button v-if="isUpdatePermitted"  type="button" @click.prevent="showEditModal(user, index)" class="btn btn-primary btn-icon-split btn-sm mr-2" title="Edit">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-pencil-alt"></i>
                                    </span>
                                    <span class="text">Edit</span>
                                </button>
                                <button v-if="isDeletePermitted" type="button" @click.prevent="showDeleteModal(user, index)" class="btn btn-danger btn-icon-split btn-sm" title="Delete">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Delete</span>
                                </button>
                              </td>
                          </tr>
                      </tbody>
                      <tfoot v-else>
                          <td colspan="4" class="text-center text-dark">No users found!</td>
                      </tfoot>
                  </table>
              </div>
            <!-- Add User Modal -->
            <div class="modal fade" data-backdrop="static" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalTitle">Add Admin User</h5>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="fullName" class="col-form-label">Full Name</label>
                                    <input type="text" v-model="data.fullName" class="form-control" placeholder="Full Name">
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-form-label">Email</label>
                                    <input type="email" v-model="data.email" class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-form-label">Password</label>
                                    <input type="password" v-model="data.password" class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation" class="col-form-label">Confirm Password</label>
                                    <input type="password" v-model="data.password_confirmation" class="form-control" placeholder="Confirm Password">
                                </div>
                                <div class="form-group">
                                    <label for="role_id" class="col-form-label">User Role</label>
                                    <select class="form-control" v-model="data.role_id">
                                        <option :value="null" disabled>Select User Role</option>
                                        <option v-for="(role, i) in roles" :key="i" :value="role.id">{{ role.roleName }}</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="closeAddModal" class="btn btn-secondary btn-sm">Close</button>
                            <button type="button" @click="addAdmin" class="btn btn-primary btn-sm">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit User Modal -->
            <div class="modal fade" data-backdrop="static" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalTitle">Edit Admin User</h5>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="fullName" class="col-form-label">Full Name</label>
                                    <input type="text" v-model="editData.fullName" class="form-control" placeholder="Full Name">
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-form-label">Email</label>
                                    <input type="email" v-model="editData.email" class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label for="role_id" class="col-form-label">User Role</label>
                                    <select class="form-control" v-model="editData.role_id">
                                        <option value="" disabled>Select User Role</option>
                                        <option v-for="(role, i) in roles" :key="i" :value="role.id">{{ role.roleName }}</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="closeEditModal" class="btn btn-secondary btn-sm">Close</button>
                            <button type="button" @click="editUser" class="btn btn-primary btn-sm">Update</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete User Modal -->
            <delete-modal></delete-modal>
          </div>
      </div>
  </div>
</template>

<script>
import deleteModal from '../components/deleteModal.vue';
import { mapGetters } from 'vuex';
export default {
    components: {
        deleteModal
    },
    data()  {
        return {
            data: {
                fullName: '',
                email: '',
                password: '',
                password_confirmation: '',
                role_id: null,
            },
            users: [],
            roles: [],
            editData: {
                fullName: '',
                email: '',
                role_id: null,
            },
            index : -1,
        }
    },
    created() {
        this.getUsers();
        this.getRoles();
    },
    methods: {
        async getUsers() {
            const res = await this.callApi('get', '/admin/user', []);
            if(res.status === 200) {
                this.users = res.data;
                // Initialize DataTable
                this.initDataTable();
            } else {
                this.error('Something went wrong in fetching users!');
            }
        },
        async getRoles() {
            const res = await this.callApi('get', '/admin/role', []);
            if(res.status === 200) {
                this.roles = res.data;
            } else {
                this.error('Something went wrong in fetching roles!');
            }
        },
        async addAdmin() {
            if(!this.data.fullName.trim()) {
                return this.error('Full name is required.');
            }
            if(!this.data.email.trim()) {
                return this.error('Email address is required.');
            }
            if(!this.data.password.trim()) {
                return this.error('Password is required.');
            }
            if(this.data.password.trim() !== this.data.password_confirmation.trim()) {
                return this.error('The password confirmation does not match.');
            }
            if(!this.data.role_id) {
                return this.error('User role is required.');
            }

            const res = await this.callApi('post', '/admin/user/add', this.data);
            if(res.status === 201) {
                this.users.unshift(res.data);
                this.success('User has been added successfully.');
                $('#addUserModal').modal('hide');
                this.clearInput();
            } else {
                if(res.status === 422) {
                    for(let i in res.data.errors) {
                        this.error(res.data.errors[i][0]);
                    }
                } else {
                    this.error('Something went wrong!');
                }
            }
        },
        async editUser() {
            if(!this.editData.fullName.trim()) {
                return this.error('Full name is required.');
            }
            if(!this.editData.email.trim()) {
                return this.error('Email address is required.');
            }
            if(!this.editData.role_id) {
                return this.error('User role is required.');
            }

            const res = await this.callApi('put', '/admin/user/edit', this.editData);
            if(res.status === 200) {
                this.users[this.index] = this.editData;
                this.success('User has been updated successfully.');
                $('#editUserModal').modal('hide');
            } else {
                if(res.status === 422) {
                    for(let i in res.data.errors) {
                        this.error(res.data.errors[i][0]);
                    }
                } else {
                    this.error('Something went wrong!');
                }
            }
        },
        closeAddModal() {
            $('#addUserModal').modal('hide');
            this.clearInput();
        },
        showAddModal() {
            $('#addUserModal').modal('show');
        },
        closeEditModal() {
            $('#editUserModal').modal('hide');
        },
        showEditModal(user, index) {
            let obj = {
                id: user.id,
                fullName: user.fullName,
                email: user.email,
                role_id: user.role_id,
            }
            this.editData = obj;
            this.index = index;
            $('#editUserModal').modal('show');
        },
        showDeleteModal(user, index) {
            $('#deleteModal').modal('show');
            const deleteModalObj = {
                deleteUrl: "/admin/user/delete",
                data: user,
                deleteItemIndex: index,
                isDeleted: false,
                msg: 'Are you sure you want to delete this user?',
                success: 'User has been deleted successfully.'
            };
            this.$store.commit('setDeleteModalObj', deleteModalObj);
        },
        clearInput() {
            this.data.fullName = '';
            this.data.email = '';
            this.data.password = '';
            this.data.password_confirmation = '';
            this.data.role_id = null;
        }
    },
    computed: {
        ...mapGetters([
            'getDeleteModalObj'
        ])
    },
    watch: {
        getDeleteModalObj(obj) {
            if(obj.isDeleted) {
                this.users.splice(obj.deleteItemIndex, 1);
            }
        }
    }
}
</script>