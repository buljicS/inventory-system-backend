export const userActionMessages = (toast, status) => {
    switch (status) {
        case "1":
            toast("success", "Your account is activated. You can login now.");
            break;

        case "2":
            toast(
                "success",
                "Your password has been changed. You can now log in."
            );
            break;
    }
};

export const getCurrentDateTime = () => {
    const currentDate = new Date();

    const day = String(currentDate.getDate()).padStart(2, "0");
    const month = String(currentDate.getMonth() + 1).padStart(2, "0");
    const year = currentDate.getFullYear();
    const hours = String(currentDate.getHours()).padStart(2, "0");
    const minutes = String(currentDate.getMinutes()).padStart(2, "0");
    const seconds = String(currentDate.getSeconds()).padStart(2, "0");

    return `${day}.${month}.${year}. ${hours}:${minutes}:${seconds}`;
};

export const handleBodyScroll = (scrollState) => {
    if (scrollState) {
        document.body.classList.add("no-scroll");
    } else {
        document.body.classList.remove("no-scroll");
    }
};
