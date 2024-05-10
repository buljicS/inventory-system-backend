import Image from "next/image";

const Icon = ({ icon }) => {
    return <Image src={icon} alt="test" width={18} height={18} />;
};

export default Icon;
