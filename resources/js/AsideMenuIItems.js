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
          icon: "fas fa-cogs",
          label: 'Settings',
          route: 'settings.index',
          permission: 'setting.view',
        },
        {
          icon: 'fas fa-user-friends',
          label: 'Providers',
          route: '#',
          permission: 'provider.view',
          children: [
            { label: 'Providers', route: 'providers.index', permission: 'provider.view', },
            { label: 'Add New', route: 'providers.create', permission: 'provider.create', }
          ]
        },
        {
          icon: "fas fa-tasks",
          label: 'Categories',
          route: '#',
          permission: 'category.view',
          children: [
            { label: 'Categories', route: 'categories.index', permission: 'category.view', },
            { label: 'Add New', route: 'categories.create', permission: 'category.view', }
          ]
        },
        {
          icon: "fas fa-suitcase",
          label: 'Services',
          route: '#',
          permission: 'service.view',
          children: [
            { label: 'Services', route: 'services.index', permission: 'service.view', },
            { label: 'Add New', route: 'services.create', permission: 'service.create', }
          ]
        },
        {
          icon: "fas fa-suitcase",
          label: 'Appointment',
          route: '#',
          permission: 'appointment.view',
          children: [
            { label: 'Appointment', route: 'appointments.index', permission: 'appointment.view', },
            { label: 'Add New', route: 'appointments.create', permission: 'appointment.create', }
          ]
        },
        {
          icon: "fas fa-suitcase",
          label: 'Customer',
          route: '#',
          permission: 'customer.view',
          children: [
            { label: 'Appointments', route: 'customer.index', permission: 'customer.view', },
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