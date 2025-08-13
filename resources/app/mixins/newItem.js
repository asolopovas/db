import {mapActions, mapState} from 'vuex'

export default {
  data() {
    return {
      itemNew: {},
    }
  },
  computed: mapState({
    structure(state) {
      return state.items[this.$route.name].structure
    },
    items(state) {
      return state.items
    },
  }),
  created() {
    this.initValues()
  },
  methods: {
    ...mapActions(['createItemAction']),
    create() {
      this.createItemAction(this).then(() => {
        this.$emit('created')
        this.resetForm()
      })
    },
    resetForm() {
      for (const prop in this.itemNew) {
        this.itemNew[prop] = ''
      }
    },
    active(group) {
      for (const prop in this.groups) {
        this.groups[prop] = false
        if (prop === group) {
          this.groups[prop] = true
        }
      }
    },
    initValues() {
      for (const prop in this.structure) {
        this.itemNew[prop] = null
      }
    },
  },
}
