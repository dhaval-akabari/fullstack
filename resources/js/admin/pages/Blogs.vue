<template>
  <div>
    <!-- Page Heading -->
      <h1 class="h3 mb-2 text-gray-800">Blogs</h1>
      
      <!-- DataTales Example -->
      <div class="card shadow mb-4">
          <div class="card-header py-3 text-right">
            <router-link :to="{ name: 'create-blog' }" v-if="isWritePermitted" class="btn btn-secondary btn-icon-split btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">Add blog</span>
            </router-link>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Title</th>
                              <th>Categories</th>
                              <th>Tags</th>
                              <th>Created At</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody v-if="blogs.length > 0">
                          <tr v-for="(blog, index) in blogs" :key="index">
                              <td>{{ blog.id }}</td>
                              <td class="text-dark font-weight-bold">{{ blog.title }}</td>
                              <td>
                                  <span v-for="(c, ci) in blog.category" :key="ci" class="blog-font bg-light text-dark mx-1 p-1 border border-grey">{{ c.categoryName }}</span>
                              </td>
                              <td>
                                  <span v-for="(t, ti) in blog.tag" :key="ti" class="blog-font bg-light text-dark mx-1 p-1 border border-grey">{{ t.tagName }}</span>
                              </td>
                              <td>{{ formatDate(blog.created_at) }}</td>
                              <td>
                                <router-link v-if="isUpdatePermitted" :to="{ name: 'edit-blog', params: { id: blog.id } }" class="btn btn-primary btn-icon-split btn-sm mr-2" title="Edit">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-pencil-alt"></i>
                                    </span>
                                    <span class="text">Edit</span>
                                </router-link>
                                <button v-if="isDeletePermitted" type="button" @click.prevent="showDeleteModal(blog, index)" class="btn btn-danger btn-icon-split btn-sm" title="Delete">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Delete</span>
                                </button>
                              </td>
                          </tr>
                      </tbody>
                      <tfoot v-else>
                          <td colspan="6" class="text-center text-dark">No blogs found!</td>
                      </tfoot>
                  </table>
                  <!-- Pagination -->
                  <pagination v-model="page" :records="total" :per-page="perPage" @paginate="getBlogs"/>
              </div>
            
            <!-- Delete Blog Modal -->
            <delete-modal></delete-modal>
          </div>
      </div>
  </div>
</template>

<script>
import Pagination from 'v-pagination-3';
import deleteModal from '../components/deleteModal.vue';
import { mapGetters } from 'vuex';
export default {
    components: {
        deleteModal,
        Pagination,
    },
    data()  {
        return {
            blogs: [],
            total: 0,
            perPage: 1,
            page: 1,
        }
    },
    created() {
        this.getBlogs();
    },
    methods: {
        async getBlogs() {
            const res = await this.callApi('get', `/admin/blogs?page=${this.page}`, []);
            if(res.status === 200) {
                this.blogs = res.data.data;
                this.total = res.data.total;
                this.perPage = res.data.per_page;
            } else {
                this.error('Something went wrong in fetching blogs!');
            }
        },
        showDeleteModal(blog, index) {
            $('#deleteModal').modal('show');
            const deleteModalObj = {
                deleteUrl: "/admin/blog/delete",
                data: { id: blog.id },
                deleteItemIndex: index,
                isDeleted: false,
                msg: 'Are you sure you want to delete this blog?',
                success: 'Blog has been deleted successfully.'
            };
            this.$store.commit('setDeleteModalObj', deleteModalObj);
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
                this.blogs.splice(obj.deleteItemIndex, 1);
            }
        }
    }
}
</script>

<style scoped>
.blog-font {
    font-size: 12px
}
</style>