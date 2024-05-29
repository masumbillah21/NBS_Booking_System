export default [
    {
      menuItems: [
        {
          icon: 'fas fa-th-large',
          label: 'Dashboard',
          route: 'dashboard',
          permission: 'dashboard.view'
        },
        {
          icon: "fas fa-user-shield",
          label: 'Permissions',
          route: '#',
          permission: 'permission.view',
          children: [
            { label: 'Permissions', route: 'permissions.index', permission: 'permission.view', },
            { label: 'Add New', route: 'permissions.create', permission: 'permission.create', }
          ]
        },
        {
          icon: "fas fa-user-lock",
          label: 'Roles',
          route: '#',
          permission: 'role.view',
          children: [
            { label: 'Roles', route: 'roles.index', permission: 'role.view' },
            { label: 'Add New', route: 'roles.create', permission: 'role.create' }
          ]
        },
        {
          icon: "fas fa-users",
          label: 'Users',
          route: '#',
          permission: 'user.view',
          children: [
            { label: 'Users', route: 'users.index', permission: 'user.view' },
            { label: 'Add New', route: 'users.create', permission: 'user.create' }
          ]
        },
        {
          icon: 'fas fa-th-large',
          label: 'Providers',
          route: '#',
          children: [
            { label: 'Providers', route: '/services-provider' },
            { label: 'Add New', route: '/services-provider/create' }
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