<template>
  <div>
    <!-- Page Heading -->
      <h1 class="h3 mb-2 text-gray-800">Category</h1>
      
      <!-- DataTales Example -->
      <div class="card shadow mb-4">
          <div class="card-header py-3 text-right">
            <button v-if="isWritePermitted" class="btn btn-secondary btn-icon-split btn-sm" @click.prevent="showAddModal">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Add category</span>
            </button>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                          <tr>
                              <th>Category Icon</th>
                              <th>Category Name</th>
                              <th>Created At</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody v-if="categories.length > 0">
                          <tr v-for="(category, index) in categories" :key="index">
                              <td><img :src="getCategoryImage(category.iconImage)" :alt="category.categoryName" width="50"></td>
                              <td class="text-dark font-weight-bold">{{ category.categoryName }}</td>
                              <td>{{ formatDate(category.created_at) }}</td>
                              <td>
                                <button v-if="isUpdatePermitted" type="button" @click.prevent="showEditModal(category)" class="btn btn-primary btn-sm btn-icon-split mr-2" title="Edit">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-pencil-alt"></i>
                                    </span>
                                    <span class="text">Edit</span>
                                </button>
                                <button v-if="isDeletePermitted" type="button" @click.prevent="showDeleteModal(category, index)" class="btn btn-danger btn-icon-split btn-sm" title="Delete">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Delete</span>
                                </button>
                              </td>
                          </tr>
                      </tbody>
                      <tfoot v-else>
                          <td colspan="4" class="text-center text-dark">No category found!</td>
                      </tfoot>
                  </table>
              </div>
            <!-- Add Category Modal -->
            <div class="modal fade" data-backdrop="static" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCategoryModalTitle">Add Category</h5>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="categoryName" class="col-form-label">Category Name</label>
                                    <input type="text" v-model="data.categoryName" class="form-control" id="categoryName" placeholder="Category name">
                                </div>
                                <div class="form-group">
                                    <label for="iconImage" class="col-form-label">Category Icon</label>
                                    <input type="file" v-if="uploadReady" @change="previewImage($event)" ref="fileupload">
                                </div>
                                <img
                                    v-if="url"
                                    :src="url"
                                    class="w-100 mt-4"
                                />
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="closeAddModal" class="btn btn-secondary btn-sm">Close</button>
                            <button type="button" @click="addCategory" class="btn btn-primary btn-sm">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Category Modal -->
            <div class="modal fade" data-backdrop="static" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCategoryModalTitle">Edit Category</h5>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="categoryName" class="col-form-label">Category Name</label>
                                    <input type="text" v-model="editData.categoryName" class="form-control" id="categoryName">
                                </div>
                                <div class="form-group">
                                    <label for="iconImage" class="col-form-label">Category Icon</label>
                                    <input type="file" @change="previewImage($event)" ref="fileupload">
                                </div>
                                <img
                                    v-if="url"
                                    :src="url"
                                    class="w-100 mt-4"
                                />
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="closeEditModal" class="btn btn-secondary btn-sm">Close</button>
                            <button type="button" @click="editCategory" class="btn btn-primary btn-sm">Update</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Category Modal -->
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
                categoryName: '',
            },
            file: '',
            url: null,
            uploadReady: true,
            categories: [],
            editData: {
                categoryName: '',
                oldImage: '',
                id: ''
            },
        }
    },
    created() {
        this.getCategory();
    },
    methods: {
        async getCategory() {
            const res = await this.callApi('get', '/admin/category', []);
            if(res.status === 200) {
                this.categories = res.data;
            } else {
                this.error('Something went wrong in fetching categories!');
            }
        },
        async addCategory() {
            if(!this.data.categoryName.trim()) {
                return this.error('Category name is required.');
            }

            const formData = new FormData();
            formData.append('iconImage', this.file);
            formData.append('categoryName', this.data.categoryName);

            const res = await this.callApi('post', '/admin/category/add', formData, {
                    'Content-Type': 'multipart/form-data',
                });
            if(res.status === 201) {
                this.categories.unshift(res.data);
                this.success('Category has been added successfully.');
                $('#addCategoryModal').modal('hide');
                this.clearInput();
            } else {
                if(res.status === 422) {
                    if(res.data.errors.categoryName) {
                        this.error(res.data.errors.categoryName);
                    }
                    if(res.data.errors.iconImage) {
                        this.error(res.data.errors.iconImage);
                    }
                } else {
                    this.error('Something went wrong!');
                }
            }
        },
        async editCategory() {
            if(!this.editData.categoryName.trim()) {
                return this.error('Category name is required.');
            }
            const formData = new FormData();
            formData.append('id', this.editData.id);
            formData.append('iconImage', this.file);
            formData.append('oldImage', this.editData.oldImage); // remove old image if exist in folder
            formData.append('categoryName', this.editData.categoryName);
            formData.append('_method', 'PUT')

            const res = await this.callApi('post', '/admin/category/edit', formData, {
                    'Content-Type': 'multipart/form-data',
                });
            if(res.status === 200) {
                this.getCategory();
                this.success('Category has been updated successfully.');
                $('#editCategoryModal').modal('hide');
                this.clearInput();
            } else {
                if(res.status === 422) {
                    if(res.data.errors.categoryName) {
                        this.error(res.data.errors.categoryName);
                    }
                    if(res.data.errors.iconImage) {
                        this.error(res.data.errors.iconImage);
                    }
                } else {
                    this.error('Something went wrong!');
                }
            }
        },
        previewImage(e) {
            this.file = e.target.files[0];
            this.url = URL.createObjectURL(this.file);
        },
        getCategoryImage(image) {
            return image ? image : 'media/category/no-image.png';
        },
        closeAddModal() {
            $('#addCategoryModal').modal('hide');
        },
        showAddModal() {
            $('#addCategoryModal').modal('show');
        },
        closeEditModal() {
            $('#editCategoryModal').modal('hide');
        },
        showEditModal(category) {
            this.url = category.iconImage;
            let obj = {
                id: category.id,
                categoryName: category.categoryName,
                oldImage: category.iconImage,
            }
            this.editData = obj;
            $('#editCategoryModal').modal('show');
        },
        showDeleteModal(category, index) {
            $('#deleteModal').modal('show');
            const deleteModalObj = {
                deleteUrl: "/admin/category/delete",
                data: category,
                deleteItemIndex: index,
                isDeleted: false,
                msg: 'Are you sure you want to delete this category?',
                success: 'Category has been deleted successfully.'
            };
            this.$store.commit('setDeleteModalObj', deleteModalObj);
        },
        clearInput() {
            this.data.categoryName = '';
            this.url = null;
            // clear file input
            this.uploadReady = false;
            this.$nextTick(() => {
        	    this.uploadReady = true;
            });
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
                this.categories.splice(obj.deleteItemIndex, 1);
            }
        }
    }
}
</script>