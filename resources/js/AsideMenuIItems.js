export default [
    {
      menuItems: [
        {
          icon: 'fas fa-th-large',
          label: 'Dashboard',
          route: '/dashboard',
        },
        {
          icon: 'fas fa-th-large',
          label: 'Services Provider',
          route: '/dashboard',
          children: [
            { label: 'Providers List', route: '/services-provider' },
            { label: 'Add New Provider', route: '/services-provider/create' }
          ]
        },
        {
          icon: "fas fa-users",
          label: 'Roles',
          route: '#',
          children: [
            { label: 'Roles', route: '/forms/form-elements' },
            { label: 'Add New', route: '/forms/form-layout' }
          ]
        },
        {
          icon: "fas fa-users",
          label: 'Permissions',
          route: '#',
          children: [
            { label: 'Permissions', route: '/forms/form-elements' },
            { label: 'Add New', route: '/forms/form-layout' }
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