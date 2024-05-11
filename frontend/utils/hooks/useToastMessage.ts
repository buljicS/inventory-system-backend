import { useToast } from "@chakra-ui/react";

const useToastMessage = () => {
    const toast = useToast();

    const showToast = (status, description) => {
        toast({
            title: "Status",
            description,
            status,
            duration: 3000,
            isClosable: true,
            position: "top-right",
        });
    };

    return showToast;
};

export default useToastMessage;
