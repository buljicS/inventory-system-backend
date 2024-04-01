"use client";
import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";

const page = () => {
    const router = useRouter();
    const [isLoggedIn, setIsLoggedIn] = useState(false);

    useEffect(() => {
        if (!sessionStorage.getItem("adminToken")) {
            router.push("/admin/login");
        } else {
            setIsLoggedIn(true);
        }
    }, []);

    return isLoggedIn && <>admin dashboard</>;
};

export default page;
