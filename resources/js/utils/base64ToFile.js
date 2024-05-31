export const base64ToFile = (base64String, filename) => {
    const parts = base64String.split(';base64,');
    const contentType = parts[0].split(':')[1];
    const raw = window.atob(base64String);
    const rawLength = raw.length;
    const uInt8Array = new Uint8Array(rawLength);
    for (let i = 0; i < rawLength; ++i) {
        uInt8Array[i] = raw.charCodeAt(i);
    }
    const blob = new Blob([uInt8Array], { type: contentType });
    return new File([blob], filename, { type: contentType });
};