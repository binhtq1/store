import React from "react";
import { Menu } from "antd";
import {
    UploadOutlined,
    UserOutlined,
    VideoCameraOutlined,
} from "@ant-design/icons";
import { Layout } from "antd";

const { Sider } = Layout;

const Sidebar = ({ collapsed }) => {
    return (
        <Sider
            className="site-sidebar"
            trigger={null}
            collapsible
            collapsed={collapsed}
        >
            <div className="logo" />
            <Menu theme="dark" mode="inline" defaultSelectedKeys={["1"]}>
                <Menu.Item key="1" icon={<UserOutlined />}>
                    nav 1
                </Menu.Item>
                <Menu.Item key="2" icon={<VideoCameraOutlined />}>
                    nav 2
                </Menu.Item>
                <Menu.Item key="3" icon={<UploadOutlined />}>
                    nav 3
                </Menu.Item>
            </Menu>
        </Sider>
    );
};

export default Sidebar;
