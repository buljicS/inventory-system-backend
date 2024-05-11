import {
    Modal,
    ModalOverlay,
    ModalContent,
    ModalHeader,
    ModalFooter,
    ModalBody,
    ModalCloseButton,
    Button,
} from "@chakra-ui/react";

const DeleteRoomsModal = ({ deleteItemIsOpen, onDeleteItemClose }) => {
    return (
        <Modal isOpen={deleteItemIsOpen} onClose={onDeleteItemClose}>
            <ModalOverlay />
            <ModalContent>
                <ModalHeader> Delete selected items</ModalHeader>
                <ModalCloseButton />
                <ModalBody>
                    Are you sure you want to delete selected items?
                </ModalBody>

                <ModalFooter>
                    <Button
                        colorScheme="blue"
                        mr={3}
                        onClick={onDeleteItemClose}
                    >
                        Close
                    </Button>
                    <Button colorScheme="red">Delete</Button>
                </ModalFooter>
            </ModalContent>
        </Modal>
    );
};

export default DeleteRoomsModal;
