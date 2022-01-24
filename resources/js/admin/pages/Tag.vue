<template>
  <div>
    <!-- Page Heading -->
      <h1 class="h3 mb-2 text-gray-800">Tag</h1>
      
      <!-- DataTales Example -->
      <div class="card shadow mb-4">
          <div class="card-header py-3 text-right">
            <button v-if="isWritePermitted" class="btn btn-secondary btn-icon-split btn-sm" @click.prevent="showAddModal">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Add tag</span>
            </button>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Tag Name</th>
                              <th>Created At</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody v-if="tags.length > 0">
                          <tr v-for="(tag, index) in tags" :key="index">
                              <td>{{ tag.id }}</td>
                              <td class="text-dark font-weight-bold">{{ tag.tagName }}</td>
                              <td>{{ formatDate(tag.created_at) }}</td>
                              <td>
                                <button v-if="isUpdatePermitted" type="button" @click.prevent="showEditModal(tag, index)" class="btn btn-primary btn-icon-split btn-sm mr-2" title="Edit">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-pencil-alt"></i>
                                    </span>
                                    <span class="text">Edit</span>
                                </button>
                                <button v-if="isDeletePermitted" type="button" @click.prevent="showDeleteModal(tag, index)" class="btn btn-danger btn-icon-split btn-sm" title="Delete">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Delete</span>
                                </button>
                              </td>
                          </tr>
                      </tbody>
                      <tfoot v-else>
                          <td colspan="4" class="text-center text-dark">No tags found!</td>
                      </tfoot>
                  </table>
              </div>
            <!-- Add Tag Modal -->
            <div class="modal fade" data-backdrop="static" id="addTagModal" tabindex="-1" role="dialog" aria-labelledby="addTagModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTagModalTitle">Add Tag</h5>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="tagName" class="col-form-label">Tag Name</label>
                                    <input type="text" v-model="data.tagName" class="form-control" id="tagName" placeholder="Tag name">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="closeAddModal" class="btn btn-secondary btn-sm">Close</button>
                            <button type="button" @click="addTag" class="btn btn-primary btn-sm">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Tag Modal -->
            <div class="modal fade" data-backdrop="static" id="editTagModal" tabindex="-1" role="dialog" aria-labelledby="editTagModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTagModalTitle">Edit Tag</h5>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="tagName" class="col-form-label">Tag Name</label>
                                    <input type="text" v-model="editData.tagName" class="form-control" id="tagName">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="closeEditModal" class="btn btn-secondary btn-sm">Close</button>
                            <button type="button" @click="editTag" class="btn btn-primary btn-sm">Update</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Tag Modal -->
            <!-- <div class="modal fade" data-backdrop="static" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalTitle">Delete Tag</h5>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this tag?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="closeDeleteModal" class="btn btn-secondary btn-sm">Close</button>
                            <button type="button" @click="deleteTag" class="btn btn-primary btn-sm">Delete</button>
                        </div>
                    </div>
                </div>
            </div> -->
            <delete-modal></delete-modal>
          </div>
      </div>
  </div>
</template>

<script>
// import common from '../../common';
import deleteModal from '../components/deleteModal.vue';
import { mapGetters } from 'vuex';
export default {
    // mixins: [common],
    components: {
        deleteModal
    },
    data()  {
        return {
            data: {
                tagName: ''
            },
            tags: [],
            //isAdding: false,
            editData: {
                tagName: ''
            },
            index: -1,
            // deleteItems: {},
            // deleteItemIndex: -1,
        }
    },
    created() {
        this.getTag();
    },
    methods: {
        async getTag() {
            const res = await this.callApi('get', '/admin/tag', []);
            if(res.status === 200) {
                this.tags = res.data;
            } else {
                this.error('Something went wrong in fetching tags!');
            }
        },
        async addTag() {
            if(!this.data.tagName.trim()) {
                return this.error('Tag name is required.');
            }
            const res = await this.callApi('post', '/admin/tag/add', this.data);
            if(res.status === 201) {
                this.tags.unshift(res.data);
                this.success('Tag has been added successfully.');
                $('#addTagModal').modal('hide');
                this.clearInput();
            } else {
                if(res.status === 422) {
                    if(res.data.errors.tagName) {
                        this.error(res.data.errors.tagName);
                    }
                } else {
                    this.error('Something went wrong!');
                }
            }
        },
        async editTag() {
            if(!this.editData.tagName.trim()) {
                return this.error('Tag name is required.');
            }
            const res = await this.callApi('put', '/admin/tag/edit', this.editData);
            if(res.status === 200) {
                this.tags[this.index] = this.editData;
                this.success('Tag has been updated successfully.');
                $('#editTagModal').modal('hide');
            } else {
                if(res.status === 422) {
                    if(res.data.errors.tagName) {
                        this.error(res.data.errors.tagName);
                    }
                } else {
                    this.error('Something went wrong!');
                }
            }
        },
        // async deleteTag() {
        //     const res = await this.callApi('delete', '/admin/tag/delete', this.deleteItems);
        //     if(res.status === 200) {
        //         this.tags.splice(this.deleteItemIndex, 1);
        //         this.success('Tag has been deleted successfully.');
        //         $('#deleteModal').modal('hide');
        //     } else {
        //         this.error('Something went wrong!');
        //     }
        // },
        closeAddModal() {
            $('#addTagModal').modal('hide');
        },
        showAddModal() {
            $('#addTagModal').modal('show');
        },
        closeEditModal() {
            $('#editTagModal').modal('hide');
        },
        showEditModal(tag, index) {
            let obj = {
                id: tag.id,
                tagName: tag.tagName
            }
            this.editData = obj;
            this.index = index;
            $('#editTagModal').modal('show');
        },
        // closeDeleteModal() {
        //     $('#deleteModal').modal('hide');
        // },
        showDeleteModal(tag, index) {
            $('#deleteModal').modal('show');
            const deleteModalObj = {
                deleteUrl: "/admin/tag/delete",
                data: tag,
                deleteItemIndex: index,
                isDeleted: false,
                msg: 'Are you sure you want to delete this tag?',
                success: 'Tag has been deleted successfully.'
            };
            this.$store.commit('setDeleteModalObj', deleteModalObj);
            // this.deleteItems = tag;
            // this.deleteItemIndex = index;
        },
        clearInput() {
            this.data.tagName = '';
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
                this.tags.splice(obj.deleteItemIndex, 1);
            }
        }
    }
}
</script>