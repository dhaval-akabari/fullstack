<template>
  <div>
    <div class="modal fade" data-backdrop="static" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalTitle">Delete confirmation!</h5>
          </div>
          <div class="modal-body">
            <p>{{ deleteModalObj.msg }}</p>
          </div>
          <div class="modal-footer">
            <button type="button" @click="closeDeleteModal" class="btn btn-secondary btn-sm">Close</button>
            <button type="button" @click="deleteItem" class="btn btn-danger btn-sm">Delete</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from 'vuex';
export default {
  data() {
    return {
    }
  },
  computed: {
    ...mapGetters({
      deleteModalObj: 'getDeleteModalObj'
    })
  },
  methods: {
    async deleteItem() {
      const res = await this.callApi('delete', this.deleteModalObj.deleteUrl, this.deleteModalObj.data);
      if(res.status === 200) {
          this.success(this.deleteModalObj.success);
          this.$store.commit('setDeleteModal', { 'deleteItemIndex': this.deleteModalObj.deleteItemIndex, 'isDeleted': true });
      } else {
          this.error('Something went wrong!');
          this.$store.commit('setDeleteModal', { 'deleteItemIndex': -1, 'isDeleted': false });
      }
      $('#deleteModal').modal('hide');
    },
    closeDeleteModal() {
      $('#deleteModal').modal('hide');
    },
  }
}
</script>