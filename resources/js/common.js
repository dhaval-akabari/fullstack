import moment from 'moment';
import { mapGetters } from 'vuex';
export default {
    data() {
        return {
            moment,
        };
    },
    methods: {
        async callApi(method, url, data, headers) {
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: data,
                    headers: headers,
                });
            } catch (error) {
                return error.response;
            }
        },
        info(title) {
            this.$toast.info("Hey!<br>" + title);
        },
        success(title) {
            this.$toast.success("Great!<br>" + title);
        },
        error(title) {
            this.$toast.error("Oops!<br>" + title);
        },
        warning(title) {
            this.$toast.warning("Hey!<br>" + title);
        },
        formatDate(dt) {
            return moment(dt).format("DD/MM/YYYY");
        },
        initDataTable() {
            this.$nextTick(function() {
                if (!$.fn.dataTable.isDataTable('#dataTable')) {
                    $('#dataTable').DataTable({
                        rowReorder: true,
                        columnDefs: [
                            { orderable: true, className: 'reorder align-middle text-center', targets: [0, 1, 2, 3] },
                            { orderable: false, targets: '_all' },
                            { className: 'align-middle text-center', targets: [0, 1, 2, 3, 4] },
                        ],
                        order:[[3,"DESC "]],
                        "responsive": true,
                    });
                }
            });
        },
        checkUserPermission(key) {
            if(!this.userPermission) {
                return true;
            }

            let isPermitted = false;
            for(let d of this.userPermission) {
                if(this.$route.name === d.name) {
                    if(d[key]) {
                       isPermitted = true;
                    }
                    break;
                }
            }
            return isPermitted;
        }
    },
    computed: {
        ...mapGetters({
            userPermission: 'getLoggedInUserPermission',
        }),
        isReadPermitted() {
            return this.checkUserPermission('read');
        },
        isWritePermitted() {
            return this.checkUserPermission('write');
        },
        isUpdatePermitted() {
            return this.checkUserPermission('update');
        },
        isDeletePermitted() {
            return this.checkUserPermission('delete');
        },
    },
};
