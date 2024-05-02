import { atom } from "recoil";
import { TItem } from "@/utils/types";

export const userAtom = atom({
    key: "User",
    default: {
        approveLogin: false,
        userId: "",
        fullName: "",
        picture: "",
        token: "",
        role: "",
        email: "",
    },
});

export const itemsRoomsAtom = atom({
    key: "Items androoms",
    default: {
        items: [] as TItem[],
        rooms: [],
    },
});
