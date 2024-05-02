import React from "react";
import { useState, useEffect } from "react";
import { useRecoilState } from "recoil";
import { userAtom } from "../atoms";

const useUserProgress = () => {
    const [progress, setProgress] = useState<number>();
    const [user, setUser] = useRecoilState(userAtom);

    const userStatus = {
        image: user.picture === null ? 0 : 1,
        information: 0,
    };

    useEffect(() => {
        const progressOne = (userStatus.information / 4) * 50;
        const ProgressTwo = userStatus.image * 100;
        const finalProgress = progressOne + ProgressTwo / 2;
        setProgress(finalProgress);
    }, [userStatus]);

    return { progress, userStatus };
};

export default useUserProgress;
