<template>
  <div>
    <!-- Page Heading -->
      <h1 class="h3 mb-2 text-gray-800">Create Blog</h1>
      
      <!-- DataTales Example -->
      <div class="card shadow mb-4">
          <div class="card-header py-3 text-right">
            <router-link :to="{ name: 'blogs' }" class="btn btn-secondary btn-icon-split btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-right"></i>
                </span>
                <span class="text">View all blogs</span>
            </router-link>
          </div>
          <div class="card-body">
            <form>
                <div class="form-group">
                    <label for="title" class="col-form-label">Title</label>
                    <input type="text" v-model="data.title" class="form-control" placeholder="Post Title">
                </div>
                <div class="form-group">
                    <label for="post" class="col-form-label">Post</label>
                    <QuillEditor 
                        v-model:content="data.post"
                        contentType="html"
                        :modules="modules"
                        toolbar="full"
                        :options="option"
                    />
                </div>
                <div class="form-group">
                    <label for="post_excerpt" class="col-form-label">Post Excerpt</label>
                    <textarea v-model="data.post_excerpt" class="form-control" rows="3" placeholder="Post Excerpt"></textarea>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label for="category_id" class="col-form-label">Categories</label>
                        <select class="form-control" multiple v-model="data.category_id">
                            <option value="" disabled>Select Categories</option>
                            <option v-for="(category, i) in categories" :key="i" :value="category.id">{{ category.categoryName }}</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="tag_id" class="col-form-label">Tags</label>
                        <select class="form-control" multiple v-model="data.tag_id">
                            <option value="" disabled>Select Tags</option>
                            <option v-for="(tag, i) in tags" :key="i" :value="tag.id">{{ tag.tagName }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="metaDescription" class="col-form-label">Post Meta Description</label>
                    <textarea v-model="data.metaDescription" class="form-control" rows="3" placeholder="Add some meta description related to this post.."></textarea>
                </div>
            </form>
            
            <button @click.prevent="add" type="button" class="btn btn-primary">Save Post</button>
          </div>
      </div>
  </div>
</template>

<script>
import { QuillEditor } from '@vueup/vue-quill'
import htmlEditButton from "quill-html-edit-button";
import '@vueup/vue-quill/dist/vue-quill.snow.css';
export default {
    components: {
        QuillEditor,
    },
    data()  {
        return {
            data: {
                title: '',
                post: '',
                post_excerpt: '',
                category_id: [],
                tag_id: [],
                metaDescription: '',
            },
            categories: [],
            tags: [],
            modules: {
                module: htmlEditButton,
            },
            option: {
                placeholder: 'Post content goes here..',
                theme: 'snow',
            }
        }
    },
    created() {
        this.getCategory();
        this.getTag();
    },
    methods: {
        
        async add() {
            if(!this.data.title.trim()) return this.error('Title is required');
            if(!this.data.post.trim()) return this.error('Post is required');
            if(!this.data.post_excerpt.trim()) return this.error('Post exerpt is required');
            if(!this.data.category_id.length) return this.error('Category is required');
            if(!this.data.tag_id.length) return this.error('Tag is required');
            if(!this.data.metaDescription.trim()) return this.error('Meta description is required');

            const res = await this.callApi('post', '/admin/blog/add', this.data);
            if(res.status === 201) {
                this.success('Blog has been added successfully.');
                this.$router.push({ name: 'blogs' });
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
        async getCategory() {
            const res = await this.callApi('get', '/admin/category', []);
            if(res.status === 200) {
                this.categories = res.data;
            } else {
                this.error('Something went wrong in fetching categories!');
            }
        },
        async getTag() {
            const res = await this.callApi('get', '/admin/tag', []);
            if(res.status === 200) {
                this.tags = res.data;
            } else {
                this.error('Something went wrong in fetching tags!');
            }
        },
    },
}
</script>

<style>
.ql-snow.ql-toolbar button,
.ql-snow .ql-toolbar button {
    width: 29px !important;
}
.ql-html-buttonGroup .ql-html-buttonCancel,
.ql-html-buttonGroup .ql-html-buttonOk {
    font-size: 12px;
}
.ql-html-buttonGroup .ql-html-buttonCancel {
    margin-right: 12px;
}
</style>