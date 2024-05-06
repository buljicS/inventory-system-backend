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

const DeleteRoomsModal = ({ deleteRoomIsOpen, onDeleteRoomClose }) => {
    return (
        <Modal isOpen={deleteRoomIsOpen} onClose={onDeleteRoomClose}>
            <ModalOverlay />
            <ModalContent>
                <ModalHeader> Delete selected rooms</ModalHeader>
                <ModalCloseButton />
                <ModalBody>
                    Are you sure you want to delete selected rooms?
                </ModalBody>

                <ModalFooter>
                    <Button
                        colorScheme="blue"
                        mr={3}
                        onClick={onDeleteRoomClose}
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
