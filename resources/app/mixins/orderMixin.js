import {mapState} from 'vuex'

export default {
  data() {
    return {
      addItem: false,
      editMode: false,
      alerts: [],
      saved: false,
    }
  },
  computed: mapState({
    order_id: state => state.order.id,
  }),
  methods: {
    cancel() {
      this.$store.commit('resetEl', this.endpoint)
      this.addItem = false
      this.editMode = false
    },
  },
}
