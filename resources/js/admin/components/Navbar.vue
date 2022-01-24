<template>
  <li class="nav-item" v-for="(menuItem, index) in permission" :key="index">
        <router-link :to="menuItem.path" class="nav-link" v-if="menuItem.read">
          <i :class="icon[menuItem.resourceName]"></i>
          <span class="ml-1">{{ menuItem.resourceName }}</span>
        </router-link>      
  </li>
</template>

<script>
export default {
  props: ['permission'],
  data() {
    return {
      icon: {
        'Dashboard': 'fas fa-fw fa-tachometer-alt',
        'Tag': 'fas fa-tags',
        'Category': 'fas fa-list',
        'Create Blog': 'fas fa-blog',
        'All Blogs': 'fas fa-blog',
        'Admin User': 'fas fa-users',
        'Role': 'fas fa-user-lock',
        'Assign Role': 'fas fa-user-plus',
      },
    }
  },
}
</script>

<style scoped>
.router-link-active {
    font-weight: bold;
    color: #fff !important;
    /* border-right: 3px solid white; */
}
.sidebar-dark .nav-item .nav-link i {
    color: inherit;
}
</style>