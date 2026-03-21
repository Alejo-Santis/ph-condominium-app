export function hasRole(user, role) {
    if (!user || !user.roles) return false;
    return user.roles.some(r => r.name === role);
}

export function hasPermission(user, permission) {
    if (!user || !user.permissions) return false;
    return user.permissions.some(p => p.name === permission);
}

export function hasAnyRole(user, roles) {
    if (!user || !user.roles) return false;
    return user.roles.some(r => roles.includes(r.name));
}

export function hasAllRoles(user, roles) {
    if (!user || !user.roles) return false;
    return roles.every(role => user.roles.some(r => r.name === role));
}

export function isSuperAdmin(user) {
    return hasRole(user, 'super_admin');
}

export function isAdminProperty(user) {
    return hasRole(user, 'admin_property');
}

export function isOwner(user) {
    return hasRole(user, 'owner');
}

export function isTenant(user) {
    return hasRole(user, 'tenant');
}

export default {
    hasRole,
    hasPermission,
    hasAnyRole,
    hasAllRoles,
    isSuperAdmin,
    isAdminProperty,
    isOwner,
    isTenant,
};
