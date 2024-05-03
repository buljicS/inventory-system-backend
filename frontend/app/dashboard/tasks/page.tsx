"use client";
import { DashboardHeader } from "@/components";
import { useRecoilState } from "recoil";
import { userAtom } from "@/utils/atoms";
const page = () => {
    return (
        <div>
            <DashboardHeader title="My tasks" />
        </div>
    );
};

export default page;
