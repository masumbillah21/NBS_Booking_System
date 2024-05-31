export default [
    {
      menuItems: [
        {
          icon: 'fas fa-th-large',
          label: 'Dashboard',
          route: 'dashboard',
        },
        {
          icon: "fas fa-users",
          label: 'Roles',
          route: '#',
          children: [
            { label: 'Roles', route: '#' },
            { label: 'Add New', route: '#' }
          ]
        },
        {
          icon: "fas fa-users",
          label: 'Permissions',
          route: '#',
          children: [
            { label: 'Permissions', route: '#' },
            { label: 'Add New', route: '#' }
          ]
        },
        {
          icon: "fas fa-users",
          label: 'Categories',
          route: '#',
          children: [
            { label: 'Categories', route: 'categories.index' },
            { label: 'Add New', route: 'categories.create' }
          ]
        },
        {
          icon: "fas fa-users",
          label: 'Services',
          route: '#',
          children: [
            { label: 'Services', route: 'services.index' },
            { label: 'Add New', route: 'services.create' }
          ]
        },
      ]
    },
    {
      name: 'OTHERS',
      menuItems: [
      ]
    }
  ]