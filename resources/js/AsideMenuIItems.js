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
          label: 'Permissions',
          route: '#',
          children: [
            { label: 'Permissions', route: 'permissions.index' },
            { label: 'Add New', route: 'permissions.create' }
          ]
        },
        {
          icon: "fas fa-users",
          label: 'Roles',
          route: '#',
          children: [
            { label: 'Roles', route: 'roles.index' },
            { label: 'Add New', route: 'roles.create' }
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