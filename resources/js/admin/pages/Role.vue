<template>
  <div>
    <!-- Page Heading -->
      <h1 class="h3 mb-2 text-gray-800">User Role</h1>
      
      <!-- DataTales Example -->
      <div class="card shadow mb-4">
          <div class="card-header py-3 text-right">
            <button v-if="isWritePermitted" class="btn btn-secondary btn-icon-split btn-sm" @click.prevent="showAddModal">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Add role</span>
            </button>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Role</th>
                              <th>Created At</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody v-if="roles.length > 0">
                          <tr v-for="(role, index) in roles" :key="index">
                              <td>{{ role.id }}</td>
                              <td class="text-dark font-weight-bold">{{ role.roleName }}</td>
                              <td>{{ formatDate(role.created_at) }}</td>
                              <td>
                                <button v-if="isUpdatePermitted" type="button" @click.prevent="showEditModal(role, index)" class="btn btn-primary btn-icon-split btn-sm mr-2" title="Edit">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-pencil-alt"></i>
                                    </span>
                                    <span class="text">Edit</span>
                                </button>
                                <button v-if="isDeletePermitted" type="button" @click.prevent="showDeleteModal(role, index)" class="btn btn-danger btn-icon-split btn-sm" title="Delete">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Delete</span>
                                </button>
                              </td>
                          </tr>
                      </tbody>
                      <tfoot v-else>
                          <td colspan="4" class="text-center text-dark">No roles found!</td>
                      </tfoot>
                  </table>
              </div>
            <!-- Add Role Modal -->
            <div class="modal fade" data-backdrop="static" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addRoleModalTitle">Add Role</h5>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="roleName" class="col-form-label">Role Name</label>
                                    <input type="text" v-model="data.roleName" class="form-control" placeholder="Role name">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="closeAddModal" class="btn btn-secondary btn-sm">Close</button>
                            <button type="button" @click="addRole" class="btn btn-primary btn-sm">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Role Modal -->
            <div class="modal fade" data-backdrop="static" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editRoleModalTitle">Edit Role</h5>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="roleName" class="col-form-label">Role Name</label>
                                    <input type="text" v-model="editData.roleName" class="form-control">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="closeEditModal" class="btn btn-secondary btn-sm">Close</button>
                            <button type="button" @click="editRole" class="btn btn-primary btn-sm">Update</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Role Modal -->
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
                roleName: ''
            },
            roles: [],
            editData: {
                roleName: ''
            },
            index: -1,
        }
    },
    created() {
        this.getRole();
    },
    methods: {
        async getRole() {
            const res = await this.callApi('get', '/admin/role', []);
            if(res.status === 200) {
                this.roles = res.data;
            } else {
                this.error('Something went wrong in fetching roles!');
            }
        },
        async addRole() {
            if(!this.data.roleName.trim()) {
                return this.error('Role name is required.');
            }
            const res = await this.callApi('post', '/admin/role/add', this.data);
            if(res.status === 201) {
                this.roles.unshift(res.data);
                this.success('Role has been added successfully.');
                $('#addRoleModal').modal('hide');
                this.clearInput();
            } else {
                if(res.status === 422) {
                    if(res.data.errors.roleName) {
                        this.error(res.data.errors.roleName);
                    }
                } else {
                    this.error('Something went wrong!');
                }
            }
        },
        async editRole() {
            if(!this.editData.roleName.trim()) {
                return this.error('Role name is required.');
            }
            const res = await this.callApi('put', '/admin/role/edit', this.editData);
            if(res.status === 200) {
                this.roles[this.index] = this.editData;
                this.success('Role has been updated successfully.');
                $('#editRoleModal').modal('hide');
            } else {
                if(res.status === 422) {
                    if(res.data.errors.roleName) {
                        this.error(res.data.errors.roleName);
                    }
                } else {
                    this.error('Something went wrong!');
                }
            }
        },
        closeAddModal() {
            $('#addRoleModal').modal('hide');
        },
        showAddModal() {
            $('#addRoleModal').modal('show');
        },
        closeEditModal() {
            $('#editRoleModal').modal('hide');
        },
        showEditModal(role, index) {
            let obj = {
                id: role.id,
                roleName: role.roleName
            }
            this.editData = obj;
            this.index = index;
            $('#editRoleModal').modal('show');
        },
        showDeleteModal(role, index) {
            $('#deleteModal').modal('show');
            const deleteModalObj = {
                deleteUrl: "/admin/role/delete",
                data: role,
                deleteItemIndex: index,
                isDeleted: false,
                msg: 'Are you sure you want to delete this role?',
                success: 'Role has been deleted successfully.'
            };
            this.$store.commit('setDeleteModalObj', deleteModalObj);
        },
        clearInput() {
            this.data.roleName = '';
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
                this.roles.splice(obj.deleteItemIndex, 1);
            }
        }
    }
}
</script>