import React, { useState, useEffect } from "react";
import { Head } from "@inertiajs/inertia-react";
import { Layout } from "antd";
import { MenuUnfoldOutlined, MenuFoldOutlined } from "@ant-design/icons";
import Sidebar from "~/Components/_partials/Sidebar";

const { Header, Content } = Layout;

const DashboardLayout = ({ title, children }) => {
    const [collapsed, setCollapsed] = useState(false);

    const handleToggle = () => setCollapsed(!collapsed);

    return (
        <>
            <Head>
                <title>{`${title} - My App`}</title>
                <meta name="description" content="Your page description" />
            </Head>
            <Layout className="site-layout">
                <Sidebar collapsed={collapsed} />
                <Layout className="site-layout__container">
                    <Header className="site-layout__header">
                        {React.createElement(
                            collapsed ? MenuUnfoldOutlined : MenuFoldOutlined,
                            {
                                className: "btn-toggle-sidebar",
                                onClick: handleToggle,
                            }
                        )}
                    </Header>
                    <Content className="site-layout__main-content">
                        {children}
                    </Content>
                </Layout>
            </Layout>
        </>
    );
};

export default DashboardLayout;
